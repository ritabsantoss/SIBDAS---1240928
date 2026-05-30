const dadosDashboard = {
    total: 1500,
    ativos: 1248,
    manutencao: 142,
    inativos: 110,
    garantiasExpiradas: 96,
    semDocumentacao: 74,
    garantias30Dias: 38,
    criticidadeElevada: 286,

    servicos: [
        { nome: "Urgência", total: 245, suporteVida: 52 },
        { nome: "Unidade de Cuidados Intensivos", total: 210, suporteVida: 86 },
        { nome: "Bloco Operatório", total: 185, suporteVida: 41 },
        { nome: "Radiologia", total: 160, suporteVida: 12 },
        { nome: "Laboratório", total: 220, suporteVida: 5 },
        { nome: "Consulta Externa", total: 175, suporteVida: 8 },
        { nome: "Internamento", total: 205, suporteVida: 34 },
        { nome: "Pediatria", total: 100, suporteVida: 18 }
    ],

    categorias: [
        { nome: "Monitorização", total: 320 },
        { nome: "Suporte de vida", total: 256 },
        { nome: "Terapia", total: 280 },
        { nome: "Diagnóstico", total: 230 },
        { nome: "Laboratório", total: 220 },
        { nome: "Esterilização", total: 94 },
        { nome: "Reabilitação", total: 100 }
    ],

    criticidade: {
        baixa: 390,
        media: 568,
        alta: 286,
        suporteVida: 256
    },

    alertas: [
        { equipamento: "Ventilador pulmonar Dräger Evita V500", situacao: "Garantia expira nos próximos 30 dias" },
        { equipamento: "Desfibrilhador Zoll R Series", situacao: "Criticidade elevada" },
        { equipamento: "Bomba de infusão B. Braun", situacao: "Sem documentação associada" },
        { equipamento: "Monitor multiparamétrico Philips MP5", situacao: "Garantia expirada" },
        { equipamento: "Ventilador neonatal", situacao: "Equipamento de suporte de vida" }
    ]
};

document.getElementById("totalEquipamentos").textContent = dadosDashboard.total;
document.getElementById("ativos").textContent = dadosDashboard.ativos;
document.getElementById("manutencao").textContent = dadosDashboard.manutencao;
document.getElementById("inativos").textContent = dadosDashboard.inativos;
document.getElementById("garantiasExpiradas").textContent = dadosDashboard.garantiasExpiradas;
document.getElementById("semDocumentacao").textContent = dadosDashboard.semDocumentacao;
document.getElementById("garantias30Dias").textContent = dadosDashboard.garantias30Dias;
document.getElementById("criticidadeElevada").textContent = dadosDashboard.criticidadeElevada;

const tabelaServicos = document.getElementById("tabelaServicos");

dadosDashboard.servicos.forEach(servico => {
    tabelaServicos.innerHTML += `
    <tr>
        <td>${servico.nome}</td>
        <td><span class="badge badge-sihem">${servico.total}</span></td>
        <td><span class="badge badge-sihem-pink">${servico.suporteVida}</span></td>
    </tr>
`;
});

const tabelaAlertas = document.getElementById("tabelaAlertas");

dadosDashboard.alertas.forEach(alerta => {
    tabelaAlertas.innerHTML += `
    <tr>
        <td>${alerta.equipamento}</td>
        <td><span class="badge badge-sihem-pink">${alerta.situacao}</span></td>
    </tr>
`;
});

new Chart(document.getElementById("graficoEstado"), {
    type: "doughnut",
    data: {
        labels: ["Ativos", "Em manutenção", "Inativos"],
        datasets: [{
            data: [
                dadosDashboard.ativos,
                dadosDashboard.manutencao,
                dadosDashboard.inativos
            ],
            backgroundColor: [
                "#005b9a",
                "#003b66",
                "#e58ea0"
            ]
        }]
    }
});

new Chart(document.getElementById("graficoCriticidade"), {
    type: "bar",
    data: {
        labels: ["Baixa", "Média", "Alta", "Suporte de vida"],
        datasets: [{
            label: "N.º de equipamentos",
            data: [
                dadosDashboard.criticidade.baixa,
                dadosDashboard.criticidade.media,
                dadosDashboard.criticidade.alta,
                dadosDashboard.criticidade.suporteVida
            ],
            backgroundColor: [
                "#005b9a",
                "#4a86b8",
                "#7aa8ca",
                "#e58ea0"
            ]
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

new Chart(document.getElementById("graficoCategoria"), {
    type: "bar",
    data: {
        labels: dadosDashboard.categorias.map(categoria => categoria.nome),
        datasets: [{
            label: "N.º de equipamentos",
            data: dadosDashboard.categorias.map(categoria => categoria.total),
             backgroundColor: "#005b9a"
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});