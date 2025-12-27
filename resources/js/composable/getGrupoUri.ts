import {Grupo} from "@/interfaces";

export const getGrupoUri = (g: Grupo,user) => (
    user.rol.clave === 'PROFESOR' ? `/grupo/${g.id}` :``
);
