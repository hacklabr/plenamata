const _prodes = {
    AC: 'ACRE',
    AM: 'AMAZONAS',
    AP: 'AMAPÁ',
    MA: 'MARANHÃO',
    MT: 'MATO GROSSO',
    PA: 'PARÁ',
    RO: 'RONDÔNIA',
    RR: 'RORAIMA',
    TO: 'TOCANTINS',
}

export function prodesState (uf) {
    return _prodes[uf] || uf
}
