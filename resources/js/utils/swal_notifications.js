/**
 * Gestión de notificaciones Sweet Alert
 */

import { inject } from 'vue';

export default function sweetAlertNotifications()
{
    const swal = inject('$swal')

    const throwSuccessMessage = ($message) => {
        console.log('Sending info message...');

        swal({
            icon: 'success',
            title: $message,
            showConfirmButton: false,
            timer: 1500,
            customClass: { popup: "swal2-custom-success" }
        });
    }

    const throwInfoMessage = ($message) => {
        console.log('Sending info message...');

        swal({
            icon: 'info',
            title: $message,
            showConfirmButton: false,
            timer: 1500
        });
    }

    const throwErrorMessage = () => {
        
    }

    const throwAcceptMessage = () => {
        
    }

    return {
        throwSuccessMessage,
        throwInfoMessage,
        throwErrorMessage,
        throwAcceptMessage
    }
}