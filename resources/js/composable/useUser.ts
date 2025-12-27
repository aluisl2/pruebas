
import {usePage} from "@inertiajs/vue3";


interface User {
    name: string;
    rol: {
        clave: string;
    };
}


// @ts-expect-error
export const useUser = (): User => usePage().props.auth.user as User
