import { ref, reactive, inject } from 'vue'
import { useRouter } from "vue-router";
import { AbilityBuilder, createMongoAbility } from '@casl/ability';
import { ABILITY_TOKEN } from '@casl/vue';
import store from '../store'
import sweetAlertNotifications from '../utils/swal_notifications';

let user = reactive({
    name: '',
    email: '',
})

export default function useAuth() {
    const processing = ref(false)
    const validationErrors = ref({})
    const router = useRouter()
    const swal = inject('$swal')
    const ability = inject(ABILITY_TOKEN)
    const { throwSuccessMessage, throwInfoMessage, throwErrorMessage, throwAcceptMessage } = sweetAlertNotifications();

    const loginForm = reactive({
        email: '',
        password: '',
        remember: false
    })

    const forgotForm = reactive({
        email: '',
    })

    const resetForm = reactive({
        email: '',
        token: '',
        password: '',
        password_confirmation: ''
    })

    // TODO Modificar los parametros que recive del controlador (agregar los que faltan)
    const registerForm = reactive({
        name: '',
        email: '',
        level: 1,
        password: '',
        password_confirmation: '',
        avatar_id: 1
    })

    const submitLogin = async () => {
        if (processing.value) return

        processing.value = true
        validationErrors.value = {}

        await axios.post('/login', loginForm)
            .then(async response => {
                await store.dispatch('auth/getUser')
                await loginUser()

                throwSuccessMessage('INICIO DE SESIÓN CORRECTO')
                await router.push({ name: 'home' });

                // evento para cerrar el popup desde /home
                const event = new Event('loggin-done');
                document.dispatchEvent(event);
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => processing.value = false)
    }

    const submitRegister = async () => {
        if (processing.value) return

        processing.value = true
        validationErrors.value = {}

        await axios.post('/register', registerForm)
            .then(async response => {
                // await store.dispatch('auth/getUser')
                // await loginUser()

                throwSuccessMessage('REGISTRADO CORRECTAMENTE');
                await router.push({ name: 'home' })

                // evento para abrir el popup de login desde /home
                const event = new Event('loggin-done');
                document.dispatchEvent(event);
            })
            .catch(error => {
                if (error.response?.data) {
                    throwErrorMessage('Error al registrar cuenta')
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => processing.value = false)
    }

    const submitForgotPassword = async () => {
        if (processing.value) return

        processing.value = true
        validationErrors.value = {}

        await axios.post('/api/forget-password', forgotForm)
            .then(async response => {
                throwSuccessMessage('Te hemos enviado el link de reset! Porfavor, comprueba tu email.')
                // await router.push({ name: 'admin.index' })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => processing.value = false)
    }

    const submitResetPassword = async () => {
        if (processing.value) return

        processing.value = true
        validationErrors.value = {}

        await axios.post('/api/reset-password', resetForm)
            .then(async response => {
                throwSuccessMessage('Contraseña cambiada correctamente!')
                await router.push({ name: 'auth.login' })
            })
            .catch(error => {
                if (error.response?.data) {
                    validationErrors.value = error.response.data.errors
                }
            })
            .finally(() => processing.value = false)
    }

    const loginUser = () => {
        user = store.state.auth.user
        // Cookies.set('loggedIn', true)
        getAbilities()
    }

    const getUser = async () => {
        if (store.getters['auth/authenticated']) {
            await store.dispatch('auth/getUser')
            await loginUser()
        }
    }

    const logout = async () => {
        if (processing.value) return

        processing.value = true

        axios.post('/logout')
            .then(response => {
                user.name = ''
                user.email = ''
                store.dispatch('auth/logout')
                router.push({ name: 'auth.login' })
            })
            .catch(error => {
                // swal({
                //     icon: 'error',
                //     title: error.response.status,
                //     text: error.response.statusText
                // })
            })
            .finally(() => {
                processing.value = false
                // Cookies.remove('loggedIn')
            })
    }

    const getAbilities = async() => {
        await axios.get('/api/abilities')
            .then(response => {
                const permissions = response.data
                const { can, rules } = new AbilityBuilder(createMongoAbility)

                can(permissions)

                ability.update(rules)
            })
    }

    const isLoggedIn = () => 
    {
        if(store.getters['auth/authenticated'])
        {
            return true;
        }
        else
        {
            return false;
        }

    };

    return {
        isLoggedIn,
        loginForm,
        registerForm,
        forgotForm,
        resetForm,
        validationErrors,
        processing,
        submitLogin,
        submitRegister,
        submitForgotPassword,
        submitResetPassword,
        user,
        getUser,
        logout,
        getAbilities
    }
}
