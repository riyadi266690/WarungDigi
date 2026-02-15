export const ServiceCategory = {
    PULSA: 'Pulsa',
    DATA: 'Paket Data',
    PLN: 'Token PLN',
    PDAM: 'PDAM',
    APARTEMEN: 'Apartemen Transit',
    BPJS: 'BPJS',
    TOPUP: 'Topup Game'
};

export const CATEGORIES = [
    { id: ServiceCategory.PULSA, icon: '📱' },
    { id: ServiceCategory.DATA, icon: '🌐' },
    { id: ServiceCategory.PLN, icon: '⚡' },
    { id: ServiceCategory.PDAM, icon: '💧' },
    { id: ServiceCategory.APARTEMEN, icon: '🏢' },
    { id: ServiceCategory.BPJS, icon: '🏥' },
    { id: ServiceCategory.TOPUP, icon: '🎮' },
];

export const MOCK_PRODUCTS = [
    { id: 'p1', name: 'Pulsa 5.000', price: 6200, buyPrice: 5100, description: 'Masa aktif +7 hari', category: ServiceCategory.PULSA },
    { id: 'p2', name: 'Pulsa 10.000', price: 11200, buyPrice: 10100, description: 'Masa aktif +15 hari', category: ServiceCategory.PULSA },
    { id: 'p3', name: 'Pulsa 20.000', price: 21200, buyPrice: 20100, description: 'Masa aktif +30 hari', category: ServiceCategory.PULSA },
    { id: 'p4', name: 'Pulsa 50.000', price: 51200, buyPrice: 50100, description: 'Masa aktif +45 hari', category: ServiceCategory.PULSA },
    { id: 'd1', name: 'Data 1GB', price: 10000, buyPrice: 9000, description: '30 Hari', category: ServiceCategory.DATA },
    { id: 'd2', name: 'Data 5GB', price: 45000, buyPrice: 40000, description: '30 Hari', category: ServiceCategory.DATA },
    { id: 'pln1', name: 'Token PLN 20.000', price: 21500, buyPrice: 20000, description: 'Token Listrik Prabayar', category: ServiceCategory.PLN },
    { id: 'pln2', name: 'Token PLN 50.000', price: 51500, buyPrice: 50000, description: 'Token Listrik Prabayar', category: ServiceCategory.PLN },
    { id: 'apt1', name: 'Bayar Sewa Apartemen', price: 0, buyPrice: 0, description: 'Sewa & Maintenance', category: ServiceCategory.APARTEMEN },
];
