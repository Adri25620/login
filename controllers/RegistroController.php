<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Usuarios;
use Model\Roles;
use MVC\Router;

class RegistroController extends ActiveRecord
{
    public function index(Router $router)
    {
        $roles = Roles::all();

        $router->render('registro/index', [
            'roles' => $roles
        ], 'layouts/layout');
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        try {
            // Validaciones iguales que las que tenías
            if (empty($_POST['us_nombres'])) {
                self::error('Los nombres son obligatorios');
                return;
            }
            if (empty($_POST['us_apellidos'])) {
                self::error('Los apellidos son obligatorios');
                return;
            }
            if (empty($_POST['us_telefono']) || strlen($_POST['us_telefono']) != 8) {
                self::error('El teléfono debe tener exactamente 8 dígitos');
                return;
            }
            if (empty($_POST['us_dpi']) || strlen($_POST['us_dpi']) != 13) {
                self::error('El DPI debe tener exactamente 13 dígitos');
                return;
            }
            if (empty($_POST['us_direccion'])) {
                self::error('La dirección es obligatoria');
                return;
            }
            if (empty($_POST['us_correo']) || !filter_var($_POST['us_correo'], FILTER_VALIDATE_EMAIL)) {
                self::error('El correo electrónico es obligatorio y debe ser válido');
                return;
            }
            if (empty($_POST['us_rol'])) {
                self::error('Debe seleccionar un rol');
                return;
            }
            if (empty($_POST['us_contrasenia']) || strlen($_POST['us_contrasenia']) < 10) {
                self::error('La contraseña debe tener al menos 10 caracteres');
                return;
            }
            if (!preg_match('/[A-Z]/', $_POST['us_contrasenia'])) {
                self::error('La contraseña debe contener al menos una letra mayúscula');
                return;
            }
            if (!preg_match('/[a-z]/', $_POST['us_contrasenia'])) {
                self::error('La contraseña debe contener al menos una letra minúscula');
                return;
            }
            if (!preg_match('/[0-9]/', $_POST['us_contrasenia'])) {
                self::error('La contraseña debe contener al menos un número');
                return;
            }
            if (!preg_match('/^[a-zA-Z0-9]+$/', $_POST['us_contrasenia'])) {
                self::error('La contraseña solo debe contener números y letras');
                return;
            }
            if ($_POST['us_contrasenia'] !== $_POST['us_confirmar_contra']) {
                self::error('Las contraseñas no coinciden');
                return;
            }

            $usuarioExistenteDpi = Usuarios::where('us_dpi', $_POST['us_dpi']);
            if (!empty($usuarioExistenteDpi)) {
                self::error('Ya existe un usuario registrado con este DPI');
                return;
            }

            $usuarioExistenteCorreo = Usuarios::where('us_correo', $_POST['us_correo']);
            if (!empty($usuarioExistenteCorreo)) {
                self::error('Ya existe un usuario registrado con este correo electrónico');
                return;
            }

            $nombreFotografia = self::procesarFotografia();
            $contrasenaHasheada = password_hash($_POST['us_contrasenia'], PASSWORD_DEFAULT);
            $tokenGenerado = bin2hex(random_bytes(32));

            $usuario = new Usuarios([
                'us_nombres' => ucwords(strtolower(trim(htmlspecialchars($_POST['us_nombres'])))),
                'us_apellidos' => ucwords(strtolower(trim(htmlspecialchars($_POST['us_apellidos'])))),
                'us_telefono' => filter_var($_POST['us_telefono'], FILTER_SANITIZE_NUMBER_INT),
                'us_direccion' => trim(htmlspecialchars($_POST['us_direccion'])),
                'us_dpi' => filter_var($_POST['us_dpi'], FILTER_SANITIZE_NUMBER_INT),
                'us_correo' => filter_var($_POST['us_correo'], FILTER_SANITIZE_EMAIL),
                'us_rol' => filter_var($_POST['us_rol'], FILTER_SANITIZE_NUMBER_INT),
                'us_contrasenia' => $contrasenaHasheada,
                'us_token' => $tokenGenerado,
                'us_fecha_creacion' => date('Y-m-d H:i'),
                'us_fecha_contrasenia' => date('Y-m-d H:i'),
                'us_foto' => $nombreFotografia,
                'us_situacion' => '1'
            ]);

            $resultado = $usuario->crear();

            if ($resultado['resultado'] == 1) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario registrado exitosamente',
                    'usuario_id' => $resultado['id']
                ]);
            } else {
                self::error('Error al registrar el usuario');
            }
        } catch (Exception $e) {
            self::internalError('Error al guardar el usuario', $e);
        }
    }

    public static function buscarAPI()
    {
        try {
            $usuarios = Usuarios::obtenerUsuariosConRol();

            foreach ($usuarios as &$usuario) {
                if (!empty($usuario['us_foto']) && is_string($usuario['us_foto'])) {
                    $usuario['foto_url'] = 'data:image/jpeg;base64,' . $usuario['us_foto'];
                } else {
                    $usuario['foto_url'] = null;
                }
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios obtenidos satisfactoriamente',
                'data' => $usuarios
            ]);
        } catch (Exception $e) {
            self::internalError('Error al obtener los usuarios', $e);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        try {
            $usuario_id = $_POST['us_id'];
            if (empty($usuario_id)) {
                self::error('ID de usuario requerido');
                return;
            }

            if (empty($_POST['us_nombres']) || empty($_POST['us_apellidos'])) {
                self::error('Nombres y apellidos son obligatorios');
                return;
            }

            // Buscamos el usuario actual
            $usuario = Usuarios::find($usuario_id);
            if (!$usuario) {
                self::error('Usuario no encontrado');
                return;
            }

            // Procesar la fotografía solo si viene nueva
            $nuevaFoto = self::procesarFotografia();
            if (!empty($nuevaFoto)) {
                $_POST['us_foto'] = $nuevaFoto;
            } else {
                $_POST['us_foto'] = $usuario['us_foto']; // Conserva la foto anterior si no se envía nueva
            }

            // Sincronizamos todos los datos normales
            $usuario->sincronizar($_POST);
            $resultado = $usuario->guardar();

            if ($resultado['resultado']) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario modificado exitosamente'
                ]);
            } else {
                self::error('Error al modificar usuario');
            }
        } catch (Exception $e) {
            self::internalError('Error al modificar el usuario', $e);
        }
    }

    public static function eliminarAPI()
    {
        try {
            $usuario_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            if (!$usuario_id) {
                self::error('ID de usuario inválido');
                return;
            }

            $resultado = Usuarios::EliminarUsuarios($usuario_id);

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario eliminado exitosamente'
                ]);
            } else {
                self::error('Error al eliminar usuario');
            }
        } catch (Exception $e) {
            self::internalError('Error al eliminar el usuario', $e);
        }
    }

    private static function procesarFotografia()
    {
        if (!isset($_FILES['us_foto']) || $_FILES['us_foto']['error'] === UPLOAD_ERR_NO_FILE) {
            return '';
        }

        $archivo = $_FILES['us_foto'];
        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Error al subir el archivo: ' . $archivo['error']);
        }

        $tamañoMaximo = 2 * 1024 * 1024;
        if ($archivo['size'] > $tamañoMaximo) {
            throw new Exception('El archivo es muy grande. Máximo permitido: 2MB');
        }

        $tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $tipoMime = finfo_file($finfo, $archivo['tmp_name']);
        finfo_close($finfo);

        if (!in_array($tipoMime, $tiposPermitidos)) {
            throw new Exception('Tipo de archivo no permitido. Solo JPG, JPEG, PNG');
        }

        $directorioDestino = __DIR__ . '/../storage/fotosUsuarios/';
        if (!is_dir($directorioDestino)) {
            if (!mkdir($directorioDestino, 0755, true)) {
                throw new Exception('No se pudo crear el directorio de fotografías');
            }
        }

        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivo = 'usuario_' . uniqid() . '_' . time() . '.' . $extension;
        $rutaCompleta = $directorioDestino . $nombreArchivo;

        if (!move_uploaded_file($archivo['tmp_name'], $rutaCompleta)) {
            throw new Exception('Error al guardar el archivo en el servidor');
        }

        $fotoBase64 = base64_encode(file_get_contents($rutaCompleta));
        return $fotoBase64;
    }

    private static function error($mensaje)
    {
        http_response_code(400);
        echo json_encode(['codigo' => 0, 'mensaje' => $mensaje]);
    }

    private static function internalError($mensaje, $e)
    {
        http_response_code(500);
        echo json_encode([
            'codigo' => 0,
            'mensaje' => $mensaje,
            'detalle' => $e->getMessage()
        ]);
    }
}
