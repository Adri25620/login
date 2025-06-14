import Swal from 'sweetalert2';
import { validarFormulario } from '../funciones';

const FormLogin = document.getElementById('FormLogin');
const BtnIniciar = document.getElementById('BtnIniciar');

const Login = async (e) => {
	e.preventDefault();
	BtnIniciar.disabled = true;

	if (!validarFormulario(FormLogin, [''])) {
		Swal.fire({
			title: "Campos vacíos",
			text: "Debe llenar todos los campos",
			icon: "info"
		});
		BtnIniciar.disabled = false;
		return;
	}

	try {
		const body = new FormData(FormLogin);
		const url = '/proyecto_uno/API/login';
		const config = {
			method: 'POST',
			body
		};
		const respuesta = await fetch(url, config);
		const data = await respuesta.json();
		console.log(data);
		
	} catch (error) {
		console.log(error);
	} finally {
		BtnIniciar.disabled = false;
	}
};

FormLogin.addEventListener('submit', Login);