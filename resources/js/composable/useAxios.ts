import { ref } from 'vue';
import axios, { AxiosInstance } from 'axios';

// Shared state (Singleton)
const modalState = ref({
    show: false,
    status: 'idle', // 'idle' | 'loading' | 'success' | 'error'
    message: '',
});

// Singleton axios instance
const axiosInstance = axios.create();

// Setup Interceptors
axiosInstance.interceptors.request.use((config) => {
    modalState.value = {
        show: true,
        status: 'loading',
        message: 'Procesando...',
    };
    return config;
}, (error) => {
    modalState.value = {
        show: true,
        status: 'error',
        message: 'Algo ha fallado al iniciar la petición.',
    };
    return Promise.reject(error);
});

axiosInstance.interceptors.response.use((response) => {
    modalState.value = {
        show: true,
        status: 'success',
        message: 'Transacción correcta',
    };
    return response;
}, (error) => {
    const errorMessage = error.response?.data?.message || error.message || 'Algo ha fallado';
    modalState.value = {
        show: true,
        status: 'error',
        message: errorMessage,
    };
    return Promise.reject(error);
});

const closeModal = () => {
    // Prevent closing while loading
    if (modalState.value.status === 'loading') return;

    modalState.value.show = false;
    setTimeout(() => {
        modalState.value.status = 'idle';
        modalState.value.message = '';
    }, 200); // Wait for transition
};

export function useAxios() {
    return {
        axios: axiosInstance,
        modalState,
        closeModal
    };
}
