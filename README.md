# SIHEM — Sistema de Inventário Hospitalar de Equipamentos Médicos

## Identificação

NOME DO PROJETO: SIHEM - Sistema de Inventário Hospitalar de Equipamentos Médicos
UNIDADE CURRICULAR: Sistemas de Informação e Bases de Dados Aplicados à Saúde (SIBDAS)
LICENCIATURA: Engenharia Biomédica
INSTITUIÇÃO: Instituto Superior de Engenharia do Porto (ISEP)
ANO LETIVO: 2025/2026
DOCENTES: Pedro Guimarães e Nuno Morgado
NOME DA AUTORA: Rita Bastos Santos
NÚMERO DE ESTUDANTE:1240928


## Descrição

A SIHEM (Sistema de Inventário Hospitalar de Equipamentos Médicos) é uma
aplicação web de gestão de inventário hospitalar, que corresponde a uma 
simulação de mercado em que uma empresa de software cria uma aplicação web 
para gestão do inventário hospitalar. 

A aplicação permite registar, consultar e gerir equipamentos médicos,
fornecedores, localizações, documentação técnica, garantias e contratos.
Inclui um dashboard com indicadores e alertas, gestão de utilizadores com
três perfis de acesso (administrador, técnico e profissional), e uma
página pública institucional com conteúdos editáveis pelo administrador. 
A aplicação inclui ainda o registo de eventos (logs) e permite exportar
dados para CSV, JSON ou PDF.

## Tecnologias

HTML · CSS · JavaScript · Bootstrap · PHP · MySQL ·
Chart.js · DataTables · Flatpickr · Font Awesome · jQuery

## Estrutura de Diretórios

sihem/
├── assets/
│   ├── bootstrap/
│   │   ├── bootstrap.bundle.min.js
│   │   └── bootstrap.min.css
│   ├── chartjs/
│   │   └── chart.umd.min.js
│   ├── css/
│   │   └── 1240928.css
│   ├── datatables/
│   │   ├── DataTables-1.13.1/
│   │   │   └── ...
│   │   ├── datatables.min.css
│   │   └── datatables.min.js
│   ├── flatpickr/
│   │   ├── flatpickr.js
│   │   └── flatpickr.min.css
│   ├── fontawesome/
│   │   ├── webfonts/
│   │   │   └── ...
│   │   └── all.min.css
│   ├── img/
│   │   ├── sihem1.png
│   │   └── ... (restantes imagens)
│   ├── jQuery/
│   │   ├── jquery-3.6.0.js
│   │   └── jquery-3.6.0.min.js
│   └── js/
│       └── 1240928.js
│
├── bd/
│   ├── 01modelofisico.sql
│   ├── 02insert.sql
│   └── modelo.dbml
│
├── config/
│   └── config.php
│
├── private/
│   ├── includes/
│   │   ├── footer.php
│   │   ├── funcoes.php
│   │   ├── header.php
│   │   ├── navbar.php
│   │   ├── sidebar.php
│   │   └── validacoes.php
│   ├── logs/
│   │   ├── .htaccess
│   │   └── app.log
│   ├── views/
│   │   ├── documentos/
│   │   │   ├── confirmar_apagar.php
│   │   │   ├── detalhes.php
│   │   │   ├── exportar.php
│   │   │   ├── lista.php
│   │   │   └── reativar.php
│   │   ├── equipamentos/
│   │   │   ├── confirmar_apagar.php
│   │   │   ├── detalhes.php
│   │   │   ├── editar.php
│   │   │   ├── exportar.php
│   │   │   ├── lista.php
│   │   │   ├── novo.php
│   │   │   └── reativar.php
│   │   ├── fornecedores/
│   │   │   ├── confirmar_apagar.php
│   │   │   ├── detalhes.php
│   │   │   ├── editar.php
│   │   │   ├── exportar.php
│   │   │   ├── lista.php
│   │   │   ├── novo.php
│   │   │   └── reativar.php
│   │   ├── localizacoes/
│   │   │   ├── confirmar_apagar.php
│   │   │   ├── detalhes.php
│   │   │   ├── editar.php
│   │   │   ├── exportar.php
│   │   │   ├── lista.php
│   │   │   ├── novo.php
│   │   │   └── reativar.php
│   │   ├── utilizadores/
│   │   │   ├── confirmar_apagar.php
│   │   │   ├── editar_password.php
│   │   │   ├── editar.php
│   │   │   ├── exportar.php
│   │   │   ├── lista.php
│   │   │   ├── novo.php
│   │   │   └── reativar.php
│   │   ├── conteudos.php
│   │   ├── dashboard.php
│   │   └── password.php
│   ├── index.php
│   ├── login.php
│   ├── logout.php
│   └── processa_login.php
│
├── public/
│   ├── uploads/
│   │   └── ... (ficheiros PDF e imagens carregados pelos utilizadores)
│   └── index.php
│
├── .gitignore
├── commits.txt
├── README.md
└── README.txt