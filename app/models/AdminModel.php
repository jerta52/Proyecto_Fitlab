<?php
require_once dirname(__DIR__, 2) . '/config/database.php';

class AdminModel
{
    private $db;
    private $configs;

    // Inicializa la conexión y carga la configuración de entidades del panel
    public function __construct()
    {
        $database = new Database();
        $this->db = $database->conectar();
        $this->configs = self::configs();
    }

    // Define las tablas que puede gestionar el administrador
    public static function configs()
    {
        return [
            'usuarios' => [
                'table' => 'usuario',
                'pk' => 'id_usuario',
                'title' => 'Usuarios registrados',
                'fields' => [
                    'nombre' => 'text',
                    'apellidos' => 'text',
                    'email' => 'email',
                    'contrasena' => 'password',
                    'telefono' => 'text',
                    'direccion' => 'text',
                    'id_rol' => 'number'
                ]
            ],
            'categorias' => [
                'table' => 'categoria',
                'pk' => 'id_categoria',
                'title' => 'Categorías',
                'fields' => [
                    'nombre_categoria' => 'text',
                    'descripcion' => 'textarea'
                ]
            ],
            'servicios' => [
                'table' => 'servicios',
                'pk' => 'id_servicio',
                'title' => 'Servicios gratuitos',
                'fields' => [
                    'nombre' => 'text',
                    'descripcion' => 'textarea',
                    'imagen' => 'file',
                    'activo' => 'boolean'
                ]
            ],
            'imagenes' => [
                'table' => 'imagenes_instalaciones',
                'pk' => 'id_imagen',
                'title' => 'Imágenes de instalaciones',
                'fields' => [
                    'titulo' => 'text',
                    'descripcion' => 'textarea',
                    'ruta' => 'file',
                    'activa' => 'boolean'
                ]
            ],
            'informacion' => [
                'table' => 'informacion_gimnasio',
                'pk' => 'id_info',
                'title' => 'Información general',
                'fields' => [
                    'nombre' => 'text',
                    'hero_titulo' => 'textarea',
                    'hero_subtitulo' => 'textarea',
                    'descripcion' => 'textarea',
                    'direccion' => 'text',
                    'telefono' => 'text',
                    'email' => 'email',
                    'mapa_url' => 'textarea'
                ]
            ],
            'horario' => [
                'table' => 'horario_gimnasio',
                'pk' => 'id_horario',
                'title' => 'Horario del gimnasio',
                'fields' => [
                    'dia' => 'text',
                    'hora_apertura' => 'time',
                    'hora_cierre' => 'time',
                    'cerrado' => 'boolean'
                ]
            ],
            'apariencia' => [
                'table' => 'configuracion_apariencia',
                'pk' => 'id_config',
                'title' => 'Aspecto visual',
                'fields' => []
            ]
        ];
    }

    // Devuelve la configuración de una entidad del panel
    public function getConfig($entity)
    {
        if (!isset($this->configs[$entity])) {
            throw new Exception('Entidad no válida');
        }

        return $this->configs[$entity];
    }

    // Lista los registros de una entidad
    public function listar($entity)
    {
        $cfg = $this->getConfig($entity);
        $query = "SELECT * FROM {$cfg['table']} ORDER BY {$cfg['pk']} DESC";

        // Los diseños se muestran en orden fijo para elegir uno de los 3 preestablecidos.
        if ($entity === 'apariencia') {
            $query = "SELECT * FROM {$cfg['table']} ORDER BY id_config ASC";
        }

        // La información general debe ser un único registro.
        if ($entity === 'informacion') {
            $query = "SELECT * FROM {$cfg['table']} ORDER BY id_info ASC LIMIT 1";
        }

        return $this->db->query($query)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene un registro concreto para editarlo
    public function obtener($entity, $id)
    {
        $cfg = $this->getConfig($entity);

        $stmt = $this->db->prepare("SELECT * FROM {$cfg['table']} WHERE {$cfg['pk']} = :id");
        $stmt->execute([':id' => (int) $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Crea un nuevo registro de la entidad indicada
    public function crear($entity, $data, $files = [])
    {
        $cfg = $this->getConfig($entity);

        if ($entity === 'apariencia') {
            throw new Exception('Los diseños visuales son preestablecidos. Solo se puede seleccionar uno.');
        }

        if ($entity === 'informacion') {
            throw new Exception('La información general es única. Usa la opción Editar.');
        }

        $values = $this->cleanData($entity, $cfg, $data, $files, false);
        $cols = array_keys($values);
        $marks = array_map(fn($column) => ':' . $column, $cols);

        $query = "INSERT INTO {$cfg['table']} (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $marks) . ")";
        $stmt = $this->db->prepare($query);

        return $stmt->execute($this->prefixParams($values));
    }

    // Actualiza un registro existente
    public function actualizar($entity, $id, $data, $files = [])
    {
        $cfg = $this->getConfig($entity);

        if ($entity === 'apariencia') {
            return $this->seleccionarDiseno($id);
        }

        $values = $this->cleanData($entity, $cfg, $data, $files, true);

        if (empty($values)) {
            return true;
        }

        $sets = array_map(fn($column) => "$column = :$column", array_keys($values));
        $params = $this->prefixParams($values);
        $params[':id'] = (int) $id;

        $query = "UPDATE {$cfg['table']} SET " . implode(', ', $sets) . " WHERE {$cfg['pk']} = :id";
        $stmt = $this->db->prepare($query);

        return $stmt->execute($params);
    }

    // Elimina un registro de la entidad indicada
    public function eliminar($entity, $id)
    {
        $cfg = $this->getConfig($entity);

        if ($entity === 'apariencia') {
            throw new Exception('No se pueden eliminar los diseños preestablecidos.');
        }

        if ($entity === 'informacion') {
            throw new Exception('La información general no se puede eliminar; solo editar.');
        }

        $stmt = $this->db->prepare("DELETE FROM {$cfg['table']} WHERE {$cfg['pk']} = :id");

        return $stmt->execute([':id' => (int) $id]);
    }

    // Selecciona el diseño visual activo y desactiva los demás
    public function seleccionarDiseno($id)
    {
        $id = (int) $id;

        $this->db->beginTransaction();
        $this->db->exec('UPDATE configuracion_apariencia SET activo = 0');

        $stmt = $this->db->prepare('UPDATE configuracion_apariencia SET activo = 1 WHERE id_config = :id');
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() < 1) {
            $this->db->rollBack();
            throw new Exception('Diseño visual no encontrado');
        }

        $this->db->commit();

        return true;
    }

    // Limpia y valida los datos antes de insertarlos o actualizarlos
    private function cleanData($entity, $cfg, $data, $files, $isEdit = false)
    {
        $out = [];

        foreach ($cfg['fields'] as $field => $type) {
            if ($type === 'file') {
                if (isset($files[$field]) && !empty($files[$field]['name'])) {
                    $out[$field] = $this->uploadImage($files[$field], $field);
                } elseif (!$isEdit && !empty($data[$field])) {
                    $out[$field] = trim($data[$field]);
                }

                continue;
            }

            if ($type === 'boolean') {
                $out[$field] = isset($data[$field]) ? 1 : 0;
                continue;
            }

            if ($type === 'password') {
                $value = trim((string) ($data[$field] ?? ''));

                if (!$isEdit && $value === '') {
                    throw new Exception('La contraseña es obligatoria al crear un usuario.');
                }

                // Si se edita un usuario y la contraseña se deja vacía, no se modifica.
                if ($value !== '') {
                    if (strlen($value) < 8) {
                        throw new Exception('La contraseña debe tener al menos 8 caracteres.');
                    }

                    $out[$field] = password_hash($value, PASSWORD_DEFAULT);
                }

                continue;
            }

            if (!isset($data[$field])) {
                continue;
            }

            $value = trim((string) $data[$field]);

            if (in_array($field, ['nombre', 'nombre_categoria', 'titulo', 'dia'], true) && $value === '') {
                throw new Exception('Hay campos obligatorios vacíos.');
            }

            if ($type === 'textarea' && in_array($field, ['descripcion', 'hero_titulo', 'hero_subtitulo'], true) && $value === '') {
                throw new Exception('Hay campos obligatorios vacíos.');
            }

            if ($type === 'email' && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Email no válido');
            }

            if ($field === 'telefono' && $value !== '' && !preg_match('/^[0-9 +()\-]{6,20}$/', $value)) {
                throw new Exception('Teléfono no válido');
            }

            if ($type === 'decimal' && (!is_numeric($value) || $value < 0)) {
                throw new Exception('El precio no puede ser negativo');
            }

            if ($type === 'number' && ($value === '' || !is_numeric($value))) {
                throw new Exception('Número no válido');
            }

            if (($type === 'number' || $type === 'decimal') && (float) $value < 0) {
                throw new Exception('No se permiten números negativos');
            }

            if ($type === 'time' && !$this->validTime($value)) {
                throw new Exception('Hora no válida');
            }

            $out[$field] = $value;
        }

        if ($entity === 'usuarios') {
            if (empty($out['email']) && !$isEdit) {
                throw new Exception('El email es obligatorio');
            }

            if (!$isEdit) {
                $out['fecha_registro'] = date('Y-m-d H:i:s');
            }

            if (!isset($out['id_rol']) || !in_array((int) $out['id_rol'], [1, 2], true)) {
                $out['id_rol'] = 2;
            }
        }

        return $out;
    }

    // Valida el formato HH:MM de los horarios
    private function validTime($value)
    {
        return (bool) preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9]$/', $value);
    }

    // Prepara los parámetros con dos puntos para PDO
    private function prefixParams($values)
    {
        $params = [];

        foreach ($values as $key => $value) {
            $params[':' . $key] = $value;
        }

        return $params;
    }

    // Valida y sube imágenes de servicios e instalaciones
    private function uploadImage($file, $field = 'imagen')
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Error al subir la imagen');
        }

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif'
        ];

        $mime = $file['type'] ?? '';

        if (function_exists('mime_content_type') && is_file($file['tmp_name'])) {
            $detected = mime_content_type($file['tmp_name']);

            if ($detected) {
                $mime = $detected;
            }
        }

        if (!isset($allowed[$mime])) {
            throw new Exception('Tipo de imagen no permitido. Usa JPG, PNG, WEBP o GIF.');
        }

        if ((int) $file['size'] > 2 * 1024 * 1024) {
            throw new Exception('La imagen supera 2MB');
        }

        $prefix = $field === 'imagen' ? 'servicio_' : 'instalacion_';
        $name = uniqid($prefix, true) . '.' . $allowed[$mime];
        $destDir = dirname(__DIR__, 2) . '/public/uploads';

        if (!is_dir($destDir)) {
            mkdir($destDir, 0775, true);
        }

        $dest = $destDir . '/' . $name;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            throw new Exception('No se pudo guardar la imagen en public/uploads');
        }

        return 'uploads/' . $name;
    }
}
