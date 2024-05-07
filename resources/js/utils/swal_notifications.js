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