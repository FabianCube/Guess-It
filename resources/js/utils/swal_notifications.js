/**
 * Gestión de notificaciones Sweet Alert
 */

import { inject } from 'vue';
import { useRouter } from 'vue-router';


export default function sweetAlertNotifications() {
    const swal = inject('$swal');

    const router = useRouter();

    const throwSuccessMessage = ($message = '¡ÉXITO!') => {
        console.log('Sending success message...');

        let backgroundMusic = new Audio('/storage/sounds/success.mp3');
        backgroundMusic.volume = 0.5;
        backgroundMusic.play();

        swal({
            icon: 'success',
            title: $message,
            showConfirmButton: false,
            timer: 1500,
            customClass: {
                popup: "swal2-custom swal2-custom-success"
            }
        });
    }

    const throwInfoMessage = ($message = 'Info') => {
        console.log('Sending info message...');

        let backgroundMusic = new Audio('/storage/sounds/info.mp3');
        backgroundMusic.volume = 0.5;
        backgroundMusic.play();

        swal({
            icon: 'info',
            title: $message,
            showConfirmButton: false,
            timer: 1500,
            customClass: {
                popup: "swal2-custom swal2-custom-info"
            }
        });
    }

    const throwErrorMessage = ($message = '¡ERROR!') => {
        console.log('Sending error message...');

        let backgroundMusic = new Audio('/storage/sounds/error.mp3');
        backgroundMusic.volume = 0.5;
        backgroundMusic.play();

        swal({
            icon: 'error',
            title: $message,
            showConfirmButton: false,
            timer: 1500,
            customClass: {
                popup: "swal2-custom swal2-custom-error"
            }
        });
    }

    const throwAcceptMessage = ($message = '¿Aceptar?') => {
        console.log('Sending accept message...');

        let backgroundMusic = new Audio('/storage/sounds/question.mp3');
        backgroundMusic.volume = 0.5;
        backgroundMusic.play();

        swal({
            icon: 'error',
            title: $message,
            showConfirmButton: true,
            timer: 1500,
            customClass: {
                popup: "swal2-custom"
            }
        });
    }

    const throwInviteMessage = ($message = '¿Aceptar?', onAccept, onReject) => {
        console.log('Sending invite message...');

        let backgroundMusic = new Audio('/storage/sounds/question.mp3');
        backgroundMusic.volume = 0.5;
        backgroundMusic.play();

        swal({
            icon: 'info',
            title: $message,
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Rechazar',
            reverseButtons: true,
            customClass: {
                popup: "swal2-custom"
            }
        }).then((result) => {
            if (result.isConfirmed) {
                onAccept();
            } else if (result.isDismissed) {
                onReject();
            }
        });
    }

    const throwRedirectMessage = ($message = 'Ok') => {
        console.log('Sending redirect message...');

        let backgroundMusic = new Audio('/storage/sounds/error.mp3');
        backgroundMusic.volume = 0.5;
        backgroundMusic.play();

        swal({
            title: 'Redireccionando...',
            text: 'El creador de la partida ha salido, serás redirigido a la página principal en 5 segundos.',
            icon: 'info',
            timer: 5000,
            timerProgressBar: true,
            customClass: {
                popup: "swal2-custom swal2-custom-info-redirect"
            },
            willClose: () => {
                // Navegar a Home después de que el mensaje se cierre
                router.push({ name: 'home' });
            }
        });
    }

    return {
        throwSuccessMessage,
        throwInfoMessage,
        throwErrorMessage,
        throwAcceptMessage,
        throwRedirectMessage,
        throwInviteMessage
    }
}