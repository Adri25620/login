<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use Model\Usuarios;
use MVC\Router;

class RegistroController extends ActiveRecord
{
    public function index(Router $router)
    {
        $router->render('registro/index', [], 'layouts/layout');
    }

    public static function guardarAPI()
    {
        getHeadersApi();

        // Validación de nombres
        $_POST['us_nombres'] = htmlspecialchars($_POST['us_nombres']);
        $cantidad_nombres = strlen($_POST['us_nombres']);

        if ($cantidad_nombres < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de caracteres de los nombres debe ser mayor a dos'
            ]);
            return;
        }

        if ($cantidad_nombres > 100) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Los nombres no pueden exceder 100 caracteres'
            ]);
            return;
        }

        // Validación de apellidos
        $_POST['us_apellidos'] = htmlspecialchars($_POST['us_apellidos']);
        $cantidad_apellidos = strlen($_POST['us_apellidos']);

        if ($cantidad_apellidos < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de caracteres de los apellidos debe ser mayor a dos'
            ]);
            return;
        }

        if ($cantidad_apellidos > 100) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Los apellidos no pueden exceder 100 caracteres'
            ]);
            return;
        }

        // Validación de teléfono
        $_POST['us_telefono'] = filter_var($_POST['us_telefono'], FILTER_VALIDATE_INT);

        if (strlen($_POST['us_telefono']) != 8) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de dígitos del teléfono debe ser igual a 8'
            ]);
            return;
        }

        // Validación de DPI
        $_POST['us_dpi'] = filter_var($_POST['us_dpi'], FILTER_SANITIZE_NUMBER_INT);

        if (strlen($_POST['us_dpi']) != 13) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI debe tener exactamente 13 dígitos'
            ]);
            return;
        }

        // Validación de dirección
        $_POST['us_direccion'] = htmlspecialchars($_POST['us_direccion']);

        if (strlen($_POST['us_direccion']) < 5) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La dirección debe tener al menos 5 caracteres'
            ]);
            return;
        }

        // Validación de correo
        $_POST['us_correo'] = filter_var($_POST['us_correo'], FILTER_SANITIZE_EMAIL);

        if (!filter_var($_POST['us_correo'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico ingresado es inválido'
            ]);
            return;
        }

        // Validación de contraseña
        if (strlen($_POST['us_contrasenia']) < 6) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La contraseña debe tener al menos 6 caracteres'
            ]);
            return;
        }

        // Validación de confirmación de contraseña
        if ($_POST['us_contrasenia'] !== $_POST['us_confirmar_contra']) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Las contraseñas no coinciden'
            ]);
            return;
        }

        // Verificar DPI único
        $usuarioExistenteDpi = Usuarios::where('us_dpi', $_POST['us_dpi']);
        if (!empty($usuarioExistenteDpi)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe un usuario registrado con este DPI'
            ]);
            return;
        }

        // Verificar correo único
        $usuarioExistenteCorreo = Usuarios::where('us_correo', $_POST['us_correo']);
        if (!empty($usuarioExistenteCorreo)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe un usuario registrado con este correo electrónico'
            ]);
            return;
        }

        try {
            $nombreFotografia = self::procesarFotografia();
            $contrasenaHasheada = password_hash($_POST['us_contrasenia'], PASSWORD_DEFAULT);
            $tokenGenerado = bin2hex(random_bytes(32));

            $data = new Usuarios([
                'us_nombres' => ucwords(strtolower(trim($_POST['us_nombres']))),
                'us_apellidos' => ucwords(strtolower(trim($_POST['us_apellidos']))),
                'us_telefono' => $_POST['us_telefono'],
                'us_direccion' => trim($_POST['us_direccion']),
                'us_dpi' => $_POST['us_dpi'],
                'us_correo' => $_POST['us_correo'],
                'us_contrasenia' => $contrasenaHasheada,
                'us_token' => $tokenGenerado,
                'us_fecha_creacion' => date('Y-m-d H:i'),
                'us_fecha_contrasenia' => date('Y-m-d H:i'),
                'us_foto' => $nombreFotografia,
                'us_situacion' => 1
            ]);

            $crear = $data->crear();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Éxito, el usuario ha sido registrado correctamente'
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarAPI()
    {
        try {
            $usuarios = Usuarios::obtenerUsuarios();

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
                'mensaje' => 'Usuarios obtenidos correctamente',
                'data' => $usuarios
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los usuarios',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['us_id'];

        // Validación de nombres
        $_POST['us_nombres'] = htmlspecialchars($_POST['us_nombres']);
        $cantidad_nombres = strlen($_POST['us_nombres']);

        if ($cantidad_nombres < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de caracteres de los nombres debe ser mayor a dos'
            ]);
            return;
        }

        if ($cantidad_nombres > 100) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Los nombres no pueden exceder 100 caracteres'
            ]);
            return;
        }

        // Validación de apellidos
        $_POST['us_apellidos'] = htmlspecialchars($_POST['us_apellidos']);
        $cantidad_apellidos = strlen($_POST['us_apellidos']);

        if ($cantidad_apellidos < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de caracteres de los apellidos debe ser mayor a dos'
            ]);
            return;
        }

        if ($cantidad_apellidos > 100) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Los apellidos no pueden exceder 100 caracteres'
            ]);
            return;
        }

        // Validación de teléfono
        $_POST['us_telefono'] = filter_var($_POST['us_telefono'], FILTER_VALIDATE_INT);

        if (strlen($_POST['us_telefono']) != 8) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad de dígitos del teléfono debe ser igual a 8'
            ]);
            return;
        }

        // Validación de DPI
        $_POST['us_dpi'] = filter_var($_POST['us_dpi'], FILTER_SANITIZE_NUMBER_INT);

        if (strlen($_POST['us_dpi']) != 13) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI debe tener exactamente 13 dígitos'
            ]);
            return;
        }

        // Validación de dirección
        $_POST['us_direccion'] = htmlspecialchars($_POST['us_direccion']);

        if (strlen($_POST['us_direccion']) < 5) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La dirección debe tener al menos 5 caracteres'
            ]);
            return;
        }

        // Validación de correo
        $_POST['us_correo'] = filter_var($_POST['us_correo'], FILTER_SANITIZE_EMAIL);

        if (!filter_var($_POST['us_correo'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electrónico ingresado es inválido'
            ]);
            return;
        }

        // Verificar DPI único (excluyendo el usuario actual)
        $usuarioExistenteDpi = Usuarios::where('us_dpi', $_POST['us_dpi']);
        if (!empty($usuarioExistenteDpi) && $usuarioExistenteDpi[0]['us_id'] != $id) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otro usuario registrado con este DPI'
            ]);
            return;
        }

        // Verificar correo único (excluyendo el usuario actual)
        $usuarioExistenteCorreo = Usuarios::where('us_correo', $_POST['us_correo']);
        if (!empty($usuarioExistenteCorreo) && $usuarioExistenteCorreo[0]['us_id'] != $id) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Ya existe otro usuario registrado con este correo electrónico'
            ]);
            return;
        }

        try {
            $data = Usuarios::find($id);

            // Procesar la fotografía solo si viene nueva
            $nuevaFoto = self::procesarFotografia();
            if (!empty($nuevaFoto)) {
                $_POST['us_foto'] = $nuevaFoto;
            } else {
                $_POST['us_foto'] = $data['us_foto']; // Conserva la foto anterior
            }

            $data->sincronizar([
                'us_nombres' => ucwords(strtolower(trim($_POST['us_nombres']))),
                'us_apellidos' => ucwords(strtolower(trim($_POST['us_apellidos']))),
                'us_telefono' => $_POST['us_telefono'],
                'us_direccion' => trim($_POST['us_direccion']),
                'us_dpi' => $_POST['us_dpi'],
                'us_correo' => $_POST['us_correo'],
                'us_foto' => $_POST['us_foto'],
                'us_situacion' => 1
            ]);

            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La información del usuario ha sido modificada exitosamente'
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al modificar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function EliminarAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

            $ejecutar = Usuarios::EliminarUsuarios($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El registro ha sido eliminado correctamente'
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar',
                'detalle' => $e->getMessage(),
            ]);
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
}