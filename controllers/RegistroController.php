<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Usuarios;

class RegistroController extends ActiveRecord
{
    public static function index(Router $router)
    {
        $router->render('registro/index', []);
    }

    public static function buscarAPI()
    {
        getHeadersApi();

        try {
            $query = "SELECT 
                    us_id,
                    us_pri_nombre,
                    us_seg_nombre,
                    us_pri_apellido,
                    us_seg_apellido,
                    us_telefono,
                    us_direccion,
                    us_dpi,
                    us_correo,
                    us_foto,
                    us_situacion
                  FROM usuarios 
                  WHERE us_situacion = '1'
                  ORDER BY us_id DESC";

            $usuarios = Usuarios::fetchArray($query);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios encontrados exitosamente',
                'data' => $usuarios
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar los usuarios',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validaciones básicas de campos obligatorios
        if (empty($_POST['us_pri_nombre'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El primer nombre es obligatorio'
            ]);
            return;
        }

        if (empty($_POST['us_pri_apellido'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El primer apellido es obligatorio'
            ]);
            return;
        }

        if (empty($_POST['us_telefono']) || strlen($_POST['us_telefono']) != 8) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El teléfono debe tener exactamente 8 dígitos'
            ]);
            return;
        }

        if (empty($_POST['us_dpi']) || strlen($_POST['us_dpi']) != 13) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI debe tener exactamente 13 dígitos'
            ]);
            return;
        }

        if (empty($_POST['us_direccion'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La dirección es obligatoria'
            ]);
            return;
        }

        if (empty($_POST['us_correo']) || !filter_var($_POST['us_correo'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico es obligatorio y debe ser válido'
            ]);
            return;
        }

        if (empty($_POST['us_contrasenia']) || strlen($_POST['us_contrasenia']) < 10) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La contraseña debe tener al menos 10 caracteres'
            ]);
            return;
        }

        // Validar complejidad de contraseña
        if (!preg_match('/[A-Z]/', $_POST['us_contrasenia'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La contraseña debe contener al menos una letra mayúscula'
            ]);
            return;
        }

        if (!preg_match('/[a-z]/', $_POST['us_contrasenia'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La contraseña debe contener al menos una letra minúscula'
            ]);
            return;
        }

        if (!preg_match('/[0-9]/', $_POST['us_contrasenia'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La contraseña debe contener al menos un número'
            ]);
            return;
        }

        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'"\\|,.<>\/?]/', $_POST['us_contrasenia'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La contraseña debe contener al menos un carácter especial'
            ]);
            return;
        }

        // Validar confirmación de contraseña
        if ($_POST['us_contrasenia'] !== $_POST['us_confirmar_contra']) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Las contraseñas no coinciden'
            ]);
            return;
        }

        try {
            // Verificar si ya existe usuario con ese DPI
            $usuarioExistenteDpi = Usuarios::where('us_dpi', $_POST['us_dpi']);
            if (!empty($usuarioExistenteDpi)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe un usuario registrado con este DPI'
                ]);
                return;
            }

            // Verificar si ya existe usuario con ese correo
            $usuarioExistenteCorreo = Usuarios::where('us_correo', $_POST['us_correo']);
            if (!empty($usuarioExistenteCorreo)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Ya existe un usuario registrado con este correo electrónico'
                ]);
                return;
            }

            // Procesar fotografía
            $nombreFotografia = '';
            try {
                $nombreFotografia = self::procesarFotografia();
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => $e->getMessage()
                ]);
                return;
            }

            // Sanitizar datos
            $us_pri_nombre = ucwords(strtolower(trim(htmlspecialchars($_POST['us_pri_nombre']))));
            $us_seg_nombre = !empty($_POST['us_seg_nombre']) ? ucwords(strtolower(trim(htmlspecialchars($_POST['us_seg_nombre'])))) : '';
            $us_pri_apellido = ucwords(strtolower(trim(htmlspecialchars($_POST['us_pri_apellido']))));
            $us_seg_apellido = !empty($_POST['us_seg_apellido']) ? ucwords(strtolower(trim(htmlspecialchars($_POST['us_seg_apellido'])))) : '';
            $us_telefono = filter_var($_POST['us_telefono'], FILTER_SANITIZE_NUMBER_INT);
            $us_direccion = trim(htmlspecialchars($_POST['us_direccion']));
            $us_dpi = filter_var($_POST['us_dpi'], FILTER_SANITIZE_NUMBER_INT);
            $us_correo = filter_var($_POST['us_correo'], FILTER_SANITIZE_EMAIL);

            $us_contrasenia_hash = password_hash($_POST['us_contrasenia'], PASSWORD_DEFAULT);
            $us_token = bin2hex(random_bytes(32));
            $us_fecha_creacion = date('Y-m-d H:i:s');
            $us_fecha_contrasenia = date('Y-m-d H:i:s');

            // Crear usuario
            $usuario = new Usuarios([
                'us_pri_nombre' => $us_pri_nombre,
                'us_seg_nombre' => $us_seg_nombre,
                'us_pri_apellido' => $us_pri_apellido,
                'us_seg_apellido' => $us_seg_apellido,
                'us_telefono' => $us_telefono,
                'us_direccion' => $us_direccion,
                'us_dpi' => $us_dpi,
                'us_correo' => $us_correo,
                'us_contrasenia' => $us_contrasenia_hash,
                'us_token' => $us_token,
                'us_fecha_creacion' => $us_fecha_creacion,
                'us_fecha_contrasenia' => $us_fecha_contrasenia,
                'us_foto' => $nombreFotografia,
                'us_situacion' => '1'
            ]);

            $resultado = $usuario->crear();

            if ($resultado['resultado'] == 1) {
                http_response_code(201);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario registrado correctamente',
                    'datos' => [
                        'us_id' => $resultado['id'],
                        'us_token' => $us_token
                    ]
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al registrar el usuario'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error interno del servidor',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    private static function procesarFotografia()
    {
        // Si no hay archivo, retornar vacío
        if (!isset($_FILES['us_foto']) || $_FILES['us_foto']['error'] === UPLOAD_ERR_NO_FILE) {
            return '';
        }

        $archivo = $_FILES['us_foto'];

        // Validar errores de subida
        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Error al subir el archivo: ' . $archivo['error']);
        }

        // Validar tamaño (máximo 2MB)
        $tamañoMaximo = 2 * 1024 * 1024; // 2MB en bytes
        if ($archivo['size'] > $tamañoMaximo) {
            throw new Exception('El archivo es muy grande. Máximo permitido: 2MB');
        }

        // Validar tipo de archivo
        $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $tipoMime = finfo_file($finfo, $archivo['tmp_name']);
        finfo_close($finfo);

        if (!in_array($tipoMime, $tiposPermitidos)) {
            throw new Exception('Tipo de archivo no permitido. Solo se permiten: JPG, JPEG, PNG');
        }

        // Usar directorio existente fotosUsuarios
        $directorioDestino = __DIR__ . '/../storage/fotosUsuarios/';

        // Verificar que el directorio existe, si no, crearlo
        if (!is_dir($directorioDestino)) {
            if (!mkdir($directorioDestino, 0755, true)) {
                throw new Exception('No se pudo crear el directorio de fotografías');
            }
        }

        // Generar nombre único para el archivo
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivo = 'usuario_' . uniqid() . '_' . time() . '.' . $extension;
        $rutaCompleta = $directorioDestino . $nombreArchivo;

        // Mover archivo al directorio de destino
        if (!move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
            throw new Exception('Error al guardar el archivo en el servidor');
        }

        // Convertir a base64 para guardar en la BD según tu estructura
        $fotoBase64 = base64_encode(file_get_contents($rutaCompleta));

        // Eliminar archivo físico si solo necesitas el base64 en BD
        // unlink($rutaCompleta);

        return $fotoBase64;
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        if (empty($_POST['us_id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de usuario es requerido'
            ]);
            return;
        }

        try {
            $usuario = Usuarios::find($_POST['us_id']);
            if (!$usuario) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Usuario no encontrado'
                ]);
                return;
            }

            // Validaciones básicas
            if (empty($_POST['us_pri_nombre']) || empty($_POST['us_pri_apellido'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Nombre y apellido son obligatorios'
                ]);
                return;
            }

            // Sincronizar datos
            $usuario->sincronizar($_POST);

            $resultado = $usuario->guardar();

            if ($resultado['resultado']) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario modificado correctamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al modificar usuario'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error interno del servidor',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function eliminarAPI()
    {
        getHeadersApi();

        if (empty($_GET['id'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'ID de usuario es requerido'
            ]);
            return;
        }

        try {
            $query = "UPDATE usuarios SET us_situacion = '0' WHERE us_id = " . intval($_GET['id']);
            $resultado = Usuarios::SQL($query);

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario eliminado correctamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al eliminar usuario'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error interno del servidor',
                'detalle' => $e->getMessage()
            ]);
        }
    }
}
