document.addEventListener("DOMContentLoaded", function () {

    // DASHBOARD
    const totalEquipamentos = document.getElementById("totalEquipamentos");

    if (totalEquipamentos && typeof Chart !== "undefined") {
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

        if (tabelaServicos) {
            dadosDashboard.servicos.forEach(servico => {
                tabelaServicos.innerHTML += `
                    <tr>
                        <td>${servico.nome}</td>
                        <td><span class="badge badge-sihem">${servico.total}</span></td>
                        <td><span class="badge badge-sihem-pink">${servico.suporteVida}</span></td>
                    </tr>
                `;
            });
        }

        const tabelaAlertas = document.getElementById("tabelaAlertas");

        if (tabelaAlertas) {
            dadosDashboard.alertas.forEach(alerta => {
                tabelaAlertas.innerHTML += `
                    <tr>
                        <td>${alerta.equipamento}</td>
                        <td><span class="badge badge-sihem-pink">${alerta.situacao}</span></td>
                    </tr>
                `;
            });
        }

        const graficoEstado = document.getElementById("graficoEstado");

        if (graficoEstado) {
            new Chart(graficoEstado, {
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
        }

        const graficoCriticidade = document.getElementById("graficoCriticidade");

        if (graficoCriticidade) {
            new Chart(graficoCriticidade, {
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
        }

        const graficoCategoria = document.getElementById("graficoCategoria");

        if (graficoCategoria) {
            new Chart(graficoCategoria, {
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
        }
    }

    // BOTÕES DE GESTÃO / MODAL ELIMINAR
    const botoesGestao = document.querySelectorAll(".btn-gestao");
    const itemSelecionado = document.getElementById("itemSelecionado");

    if (botoesGestao.length > 0 && itemSelecionado) {
        botoesGestao.forEach(botao => {
            botao.addEventListener("click", function () {
                const nome = this.getAttribute("data-nome");
                itemSelecionado.textContent = nome;
            });
        });
    }

    // EQUIPAMENTOS - NAVEGAÇÃO ENTRE PASSOS
    const tabsEquipamento = document.querySelectorAll("#equipamentoTabs button");
    const botoesSeguinte = document.querySelectorAll(".btn-seguinte");
    const botoesAnterior = document.querySelectorAll(".btn-anterior");
    const avisoPassos = document.getElementById("aviso-passos");

    function mostrarPasso(indice) {
        if (!tabsEquipamento[indice]) return;

        const tab = new bootstrap.Tab(tabsEquipamento[indice]);
        tab.show();

        if (avisoPassos) {
            avisoPassos.classList.add("d-none");
            avisoPassos.textContent = "";
        }
    }

    function mostrarAviso(mensagem) {
        if (!avisoPassos) return;

        avisoPassos.textContent = mensagem;
        avisoPassos.classList.remove("d-none");
    }

    function campoPreenchido(id) {
        const campo = document.getElementById(id);

        if (!campo) return false;

        if (campo.tagName === "SELECT") {
            const opcaoSelecionada = campo.options[campo.selectedIndex];

            if (!opcaoSelecionada) return false;
            if (opcaoSelecionada.disabled) return false;
            if (campo.value.trim() === "") return false;

            return true;
        }

        return campo.value.trim() !== "";
    }

    function validarPasso(indice) {
        if (indice === 0) {
            if (
                !campoPreenchido("codigo_interno") ||
                !campoPreenchido("designacao") ||
                !campoPreenchido("categoria")
            ) {
                mostrarAviso("Preencha código interno, designação e categoria antes de avançar.");
                return false;
            }
        }

        if (indice === 1) {
            if (
                !campoPreenchido("estado_atual") ||
                !campoPreenchido("criticidade")
            ) {
                mostrarAviso("Preencha o estado atual e a criticidade antes de avançar.");
                return false;
            }
        }

        if (indice === 2) {
            const fornecedoresSelecionados = document.querySelectorAll(
                'select[name^="fornecedores"][name$="[id_fornecedor]"]'
            );

            if (fornecedoresSelecionados.length === 0) {
                mostrarAviso("Adicione pelo menos um fornecedor antes de avançar.");
                return false;
            }

            for (const fornecedor of fornecedoresSelecionados) {
                const opcaoSelecionada = fornecedor.options[fornecedor.selectedIndex];

                if (!opcaoSelecionada || opcaoSelecionada.disabled || fornecedor.value.trim() === "") {
                    mostrarAviso("Selecione o fornecedor em todos os blocos adicionados.");
                    return false;
                }
            }
        }

        if (indice === 3) {
            if (!campoPreenchido("localizacao_associada")) {
                mostrarAviso("Selecione uma localização antes de avançar.");
                return false;
            }
        }

        return true;
    }

    if (botoesSeguinte.length > 0) {
        botoesSeguinte.forEach(botao => {
            botao.addEventListener("click", function () {
                const passoAtual = parseInt(this.getAttribute("data-passo-atual"));

                if (validarPasso(passoAtual)) {
                    mostrarPasso(passoAtual + 1);
                }
            });
        });
    }

    if (botoesAnterior.length > 0) {
        botoesAnterior.forEach(botao => {
            botao.addEventListener("click", function () {
                const passoAtual = parseInt(this.getAttribute("data-passo-atual"));
                mostrarPasso(passoAtual - 1);
            });
        });
    }

    const formularioEquipamento = document.getElementById("form-equipamento");

    if (tabsEquipamento.length > 0 && formularioEquipamento) {
        tabsEquipamento.forEach((tab, indice) => {
            tab.addEventListener("show.bs.tab", function (event) {
                const tabAtiva = document.querySelector("#equipamentoTabs .nav-link.active");
                const indiceAtual = Array.from(tabsEquipamento).indexOf(tabAtiva);

                if (indice > indiceAtual && !validarPasso(indiceAtual)) {
                    event.preventDefault();
                }
            });
        });
    }

    // EQUIPAMENTOS - ADICIONAR / REMOVER DOCUMENTOS
    const botaoAdicionarDocumento = document.getElementById("adicionar-documento");

    if (botaoAdicionarDocumento) {
        let contadorDocumentos = 1;

        botaoAdicionarDocumento.addEventListener("click", function () {
            const container = document.getElementById("documentos-container");
            const primeiroDocumento = container.querySelector(".documento-bloco");
            const novoDocumento = primeiroDocumento.cloneNode(true);

            novoDocumento.querySelector("h6").textContent =
                "Documento " + (contadorDocumentos + 1);

            novoDocumento.querySelectorAll("input, textarea, select").forEach(function (campo) {
                campo.value = "";

                if (campo.name) {
                    campo.name = campo.name.replace(
                        /documentos\[\d+\]/,
                        "documentos[" + contadorDocumentos + "]"
                    );
                }
            });

            const botaoRemover = novoDocumento.querySelector(".remover-documento");

            botaoRemover.classList.remove("d-none");

            botaoRemover.addEventListener("click", function () {
                novoDocumento.remove();
            });

            container.appendChild(novoDocumento);
            contadorDocumentos++;
        });
    }

    // EQUIPAMENTOS - ADICIONAR / REMOVER COMPONENTES
    const botaoAdicionarComponente = document.getElementById("adicionar-componente");

    if (botaoAdicionarComponente) {

        let contadorComponentes = 0;

        botaoAdicionarComponente.addEventListener("click", function () {

            const container = document.getElementById("componentes-container");

            const novoComponente = document.createElement("div");

            novoComponente.classList.add(
                "componente-bloco",
                "border",
                "rounded-4",
                "p-3",
                "mb-3"
            );

            novoComponente.innerHTML = `
            <div class="row align-items-end">

                <div class="col-md-3">
                    <label class="form-label">Código</label>
                    <input type="text"
                           class="form-control"
                           name="componentes[${contadorComponentes}][codigo_componente]">
                </div>

                <div class="col-md-5">
                    <label class="form-label">Nome</label>
                    <input type="text"
                           class="form-control"
                           name="componentes[${contadorComponentes}][nome_componente]">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Estado</label>

                    <select class="form-select"
                            name="componentes[${contadorComponentes}][estado_componente]">

                        <option selected disabled>
                            Escolha...
                        </option>

                        <option>Ativo</option>
                        <option>Inativo</option>
                        <option>Em manutenção</option>

                    </select>
                </div>

                <div class="col-md-1">
                    <button type="button"
                            class="btn btn-outline-danger remover-componente">

                        <i class="fa-solid fa-trash"></i>

                    </button>
                </div>

            </div>
        `;

            novoComponente
                .querySelector(".remover-componente")
                .addEventListener("click", function () {
                    novoComponente.remove();
                });

            container.appendChild(novoComponente);

            contadorComponentes++;

        });

    }

    // EQUIPAMENTOS - ADICIONAR / REMOVER FORNECEDORES
    const botaoAdicionarFornecedor = document.getElementById("adicionar-fornecedor");

    if (botaoAdicionarFornecedor) {

        let contadorFornecedores = 1;

        botaoAdicionarFornecedor.addEventListener("click", function () {

            const container = document.getElementById("fornecedores-container");
            const primeiroFornecedor = container.querySelector(".fornecedor-bloco");
            const novoFornecedor = primeiroFornecedor.cloneNode(true);

            novoFornecedor.querySelectorAll("input, select").forEach(function (campo) {

                campo.value = "";

                if (campo.name) {
                    campo.name = campo.name.replace(
                        /fornecedores\[\d+\]/,
                        "fornecedores[" + contadorFornecedores + "]"
                    );
                }

            });

            const botaoRemover = novoFornecedor.querySelector(".remover-fornecedor");

            botaoRemover.classList.remove("d-none");

            botaoRemover.addEventListener("click", function () {
                novoFornecedor.remove();
            });

            container.appendChild(novoFornecedor);

            contadorFornecedores++;
        });
    }

});