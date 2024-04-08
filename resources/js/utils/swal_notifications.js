/**
 * Gestión de notificaciones Sweet Alert
 */

import { inject } from 'vue';

export default function sweetAlertNotifications()
{
    const swal = inject('$swal')

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

    return {
        throwSuccessMessage,
        throwInfoMessage,
        throwErrorMessage,
        throwAcceptMessage
    }
}