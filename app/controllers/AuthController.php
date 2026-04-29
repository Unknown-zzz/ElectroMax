<?php
class AuthController extends Controller {
    private User $user;
    private Category $category;

    public function __construct() {
        $this->user     = new User();
        $this->category = new Category();
    }

    public function login(): void {
        if (isLoggedIn()) { $this->redirect('store/index'); return; }
        $categorias = $this->category->all();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $user     = $this->user->login($email, $password);
            if ($user) {
                $_SESSION['user_id']     = $user['id'];
                $_SESSION['user_nombre'] = $user['nombre'];
                $_SESSION['user_rol']    = $user['rol'];
                $_SESSION['flash']       = ['type' => 'success', 'msg' => '¡Bienvenido, ' . $user['nombre'] . '!'];
                $dest = in_array($user['rol'], ['admin','vendedor','inventario'])
                    ? 'admin/dashboard' : 'store/index';
                $this->redirect($dest);
                return;
            }
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Credenciales incorrectas.'];
        }
        $this->render('layouts/header', ['title' => 'Iniciar Sesión', 'categorias' => $categorias]);
        $this->render('auth/login');
        $this->render('layouts/footer');
    }

    public function register(): void {
        if (isLoggedIn()) { $this->redirect('store/index'); return; }
        $categorias = $this->category->all();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre   = trim($_POST['nombre'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $telefono = trim($_POST['telefono'] ?? '');
            if (strlen($password) < 6) {
                $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'La contraseña debe tener al menos 6 caracteres.'];
            } else {
                $result = $this->user->register($nombre, $email, $password, $telefono);
                if ((int)$result['p_id'] > 0) {
                    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Cuenta creada. Puedes iniciar sesión.'];
                    $this->redirect('auth/login');
                    return;
                }
                $_SESSION['flash'] = ['type' => 'danger', 'msg' => $result['p_error']];
            }
        }
        $this->render('layouts/header', ['title' => 'Registro', 'categorias' => $categorias]);
        $this->render('auth/register');
        $this->render('layouts/footer');
    }

    public function logout(): void {
        session_destroy();
        header('Location: ' . APP_URL . '/index.php?r=store/index');
        exit;
    }
}
