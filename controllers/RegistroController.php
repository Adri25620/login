<?php

namespace Controllers;

use Exception;
use Model\Usuarios;
use Model\ActiveRecord;
use MVC\Router;

class RegistroController extends ActiveRecord
{
    public static function index(Router $router)
    {
        $router->render('registro/index', [], 'layouts/layout');
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        try {
            // Validar primer nombre
            $_POST['us_pri_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['us_pri_nombre']))));
            $cantidad_nombres = strlen($_POST['us_pri_nombre']);
            if ($cantidad_nombres < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El primer nombre debe tener al menos 2 caracteres'
                ]);
                return;
            }

            // Validar segundo nombre
            $_POST['us_seg_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['us_seg_nombre']))));

            // Validar primer apellido
            $_POST['us_pri_apellido'] = ucwords(strtolower(trim(htmlspecialchars($_POST['us_pri_apellido']))));
            $cantidad_apellido = strlen($_POST['us_pri_apellido']);
            if ($cantidad_apellido < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El primer apellido debe tener al menos 2 caracteres'
                ]);
                return;
            }

            // Validar segundo apellido
            $_POST['us_seg_apellido'] = ucwords(strtolower(trim(htmlspecialchars($_POST['us_seg_apellido']))));

            // Validar teléfono
            $_POST['us_telefono'] = filter_var($_POST['us_telefono'], FILTER_SANITIZE_NUMBER_INT);
            if (strlen($_POST['us_telefono']) != 8) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El teléfono debe tener exactamente 8 dígitos'
                ]);
                return;
            }

            // Validar dirección
            $_POST['us_direccion'] = trim(htmlspecialchars($_POST['us_direccion']));
            if (strlen($_POST['us_direccion']) < 10) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La dirección debe tener al menos 10 caracteres'
                ]);
                return;
            }

            // Validar DPI
            $_POST['us_dpi'] = filter_var($_POST['us_dpi'], FILTER_SANITIZE_NUMBER_INT);
            if (strlen($_POST['us_dpi']) != 13) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El DPI debe tener exactamente 13 dígitos'
                ]);
                return;
            }

            // Validar correo
            $_POST['us_correo'] = filter_var($_POST['us_correo'], FILTER_SANITIZE_EMAIL);
            if (!filter_var($_POST['us_correo'], FILTER_VALIDATE_EMAIL)) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El correo electrónico no es válido'
                ]);
                return;
            }

            // Verificar si el correo ya existe
            $usuarioExistente = Usuarios::where('us_correo', $_POST['us_correo']);
            if ($usuarioExistente) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El correo electrónico ya está registrado'
                ]);
                return;
            }

            // Verificar si el DPI ya existe
            $dpiExistente = Usuarios::where('us_dpi', $_POST['us_dpi']);
            if ($dpiExistente) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El DPI ya está registrado'
                ]);
                return;
            }

            // Validar contraseña ANTES de hashear
            if (strlen($_POST['us_contrasenia']) < 8) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La contraseña debe tener al menos 8 caracteres'
                ]);
                return;
            }

            // Validar que las contraseñas coincidan ANTES de hashear
            if ($_POST['us_contrasenia'] !== $_POST['us_confirmar_contra']) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Las contraseñas no coinciden'
                ]);
                return;
            }

            // Ahora sí hashear la contraseña
            $_POST['us_contrasenia'] = password_hash($_POST['us_contrasenia'], PASSWORD_DEFAULT);

            // Generar token único
            $_POST['us_token'] = bin2hex(random_bytes(32));

            // Establecer fechas
            $_POST['us_fecha_creacion'] = date('Y-m-d');
            $_POST['us_fecha_contrasenia'] = date('Y-m-d');

            // Manejar fotografía
            $rutaFotografia = null;
            if (isset($_FILES['us_fotografia']) && $_FILES['us_fotografia']['error'] !== UPLOAD_ERR_NO_FILE) {
                $file = $_FILES['us_fotografia'];
                $fileName = $file['name'];
                $fileTmpName = $file['tmp_name'];
                $fileSize = $file['size'];
                $fileError = $file['error'];

                if ($fileError === 0) {
                    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png'];

                    if (!in_array($fileExtension, $allowed)) {
                        http_response_code(400);
                        echo json_encode([
                            'codigo' => 0,
                            'mensaje' => 'Solo se permiten archivos JPG, PNG o JPEG'
                        ]);
                        return;
                    }

                    if ($fileSize >= 2000000) {
                        http_response_code(400);
                        echo json_encode([
                            'codigo' => 0,
                            'mensaje' => 'La imagen debe pesar menos de 2MB'
                        ]);
                        return;
                    }

                    // Crear directorio si no existe
                    $uploadDir = __DIR__ . "/../../storage/fotografias/";
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $nombreArchivo = $_POST['us_dpi'] . '.' . $fileExtension;
                    $rutaCompleta = $uploadDir . $nombreArchivo;
                    $rutaFotografia = "storage/fotografias/" . $nombreArchivo;

                    if (!move_uploaded_file($fileTmpName, $rutaCompleta)) {
                        http_response_code(500);
                        echo json_encode([
                            'codigo' => 0,
                            'mensaje' => 'Error al subir la fotografía'
                        ]);
                        return;
                    }
                }
            }

            // Crear el usuario
            $usuario = new Usuarios([
                'us_pri_nombre' => $_POST['us_pri_nombre'],
                'us_seg_nombre' => $_POST['us_seg_nombre'],
                'us_pri_apellido' => $_POST['us_pri_apellido'],
                'us_seg_apellido' => $_POST['us_seg_apellido'],
                'us_telefono' => $_POST['us_telefono'],
                'us_direccion' => $_POST['us_direccion'],
                'us_dpi' => $_POST['us_dpi'],
                'us_correo' => $_POST['us_correo'],
                'us_contrasenia' => $_POST['us_contrasenia'],
                'us_token' => $_POST['us_token'],
                'us_fecha_creacion' => $_POST['us_fecha_creacion'],
                'us_fecha_contrasenia' => $_POST['us_fecha_contrasenia'],
                'us_foto' => $rutaFotografia,
                'us_situacion' => '1'
            ]);

            $resultado = $usuario->crear();

            if ($resultado['resultado'] == 1) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario registrado correctamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al registrar el usuario',
                    'detalle' => $resultado
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

    public static function buscarAPI()
    {
        getHeadersApi();
        
        try {
            $usuarios = Usuarios::all();
            
            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Datos encontrados',
                'datos' => $usuarios
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al buscar usuarios',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();
        
        try {
            $id = filter_var($_POST['us_id'], FILTER_VALIDATE_INT);
            if (!$id) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de usuario inválido'
                ]);
                return;
            }

            $usuario = Usuarios::find($id);
            if (!$usuario) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Usuario no encontrado'
                ]);
                return;
            }

            // Aplicar las mismas validaciones que en guardar
            
            // Actualizar campos
            $usuario->us_pri_nombre = ucwords(strtolower(trim(htmlspecialchars($_POST['us_pri_nombre']))));
            $usuario->us_seg_nombre = ucwords(strtolower(trim(htmlspecialchars($_POST['us_seg_nombre']))));
            $usuario->us_pri_apellido = ucwords(strtolower(trim(htmlspecialchars($_POST['us_pri_apellido']))));
            $usuario->us_seg_apellido = ucwords(strtolower(trim(htmlspecialchars($_POST['us_seg_apellido']))));
            $usuario->us_telefono = $_POST['us_telefono'];
            $usuario->us_direccion = trim(htmlspecialchars($_POST['us_direccion']));
            
            $resultado = $usuario->actualizar();
            
            if ($resultado['resultado'] == 1) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario actualizado correctamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al actualizar el usuario'
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
        
        try {
            $id = filter_var($_POST['us_id'], FILTER_VALIDATE_INT);
            if (!$id) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de usuario inválido'
                ]);
                return;
            }

            $usuario = Usuarios::find($id);
            if (!$usuario) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Usuario no encontrado'
                ]);
                return;
            }

            // Cambiar situación en lugar de eliminar físicamente
            $usuario->us_situacion = '0';
            $resultado = $usuario->actualizar();
            
            if ($resultado['resultado'] == 1) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario eliminado correctamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al eliminar el usuario'
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