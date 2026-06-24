================================================================================
README - SIHEM
Sistema de Inventário Hospitalar de Equipamentos Médicos
================================================================================

NOME DO PROJETO: SIHEM - Sistema de Inventário Hospitalar de Equipamentos Médicos
UNIDADE CURRICULAR: Sistemas de Informação e Bases de Dados Aplicados à Saúde (SIBDAS)
LICENCIATURA: Engenharia Biomédica
INSTITUIÇÃO: Instituto Superior de Engenharia do Porto (ISEP)
ANO LETIVO: 2025/2026

================================================================================

DOCENTES: Pedro Guimarães e Nuno Morgado
NOME DA AUTORA: Rita Bastos Santos
NÚMERO DE ESTUDANTE:1240928

================================================================================
DESCRIÇÃO DA APLICAÇÃO
================================================================================

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

================================================================================
INSTALAÇÃO E EXECUÇÃO
================================================================================

INSTALAÇÃO E EXECUÇÃO
================================================================================

Para executar localmente a aplicação (Laragon):

1. Copiar a pasta do projeto para a diretoria www Laragon.
   Estrutura obrigatória: C:\laragon\www\sibdas\1240928\sihem\

2. Carregar no botão "Start All" no Laragon, para o iniciar

3. Criar uma base de dados vazia no servidor local.

4. Importar os ficheiros de base de dados pela seguinte ordem:
   - bd/01modelofisico.sql  (estrutura)
   - bd/02insert.sql        (dados)

5. Aceder no browser:
   http://127.0.0.1/sibdas/1240928/sihem/public/index.php

   Área privada (login):
   http://127.0.0.1/sibdas/1240928/sihem/private/login.php

6. Assegurar que o ficheiro config/config.php tem as credenciais da base de
   dados correta (host, port, nome da BD, utilizador e password)

Nota: A implementação da base de dados para o projeto deve ser feita no servidor
      utilizado nas aulas práticas do módulo de Bases de Dados, ou seja:
    - Servidor: vsgate-s1.dei.isep.ipp.pt:10464
    - Base de dados: db1240928
    - Credenciais acesso servidor: ficheiro config

================================================================================
CREDENCIAIS DE ACESSO
================================================================================

Existem três perfis principais de utilizador na aplicação:

Perfil: Administrador
  Email:    ritasantos@sihem.pt
  Password: Sihem001!

Perfil: Técnico
  Email:    mariosilva@sihem.pt
  Password: Sihem002!

Perfil: Profissional
  Email:    alexandrarosas@sihem.pt
  Password: Sihem003!
  Acesso:   Limitado a Lista e Detalhes de Equipamentos. Não pode exportar. 
            Só tem acesso a ativos. Não pode desativar nem reativar.

================================================================================
INSTRUÇÕES PARA TESTE DAS PRINCIPAIS FUNCIONALIDADES
================================================================================

1. PÁGINA PÚBLICA
   - Aceder ao endereço público acima indicado.
   - Verificar as secções Hero, Estatísticas, Serviços, FAQ e Contactos.

2. LOGIN
   - Aceder à área privada pelo botão "Área Privada".
   - Autenticar com qualquer uma das credenciais acima. 
     Administrador para testar todas as funcionalidades.
   - Verificar que o acesso é negado com credenciais inválidas.

3. DASHBOARD
   - Após login, clicar em "Dashboard" na sidebar.
   - Verificar os cards de indicadores, gráficos e tabela de alertas.

4. EQUIPAMENTOS
   - Aceder a "Equipamentos" na sidebar.
   - Criar um novo equipamento (mínimo: código interno, designação,
     categoria, localização e pelo menos um fornecedor associado).
   - Editar o equipamento criado.
   - Consultar a página de detalhe.
   - Testar os filtros avançados por categoria, estado e criticidade.
   - Exportar a listagem em CSV, JSON ou PDF.
   - Desativar e reativar o equipamento.

5. FORNECEDORES
   - Aceder a "Fornecedores" na sidebar.
   - Criar, editar, desativar e reativar um fornecedor.

6. LOCALIZAÇÕES
   - Aceder a "Localizações" na sidebar.
   - Criar, editar, desativar e reativar uma localização.

7. DOCUMENTAÇÃO
   - Aceder a "Documentação" na sidebar.
   - Verificar a listagem de documentos associados aos equipamentos.

8. GESTÃO DE CONTEÚDOS PÚBLICOS
   - Autenticar como administrador.
   - Aceder a "Conteúdos Públicos" na sidebar.
   - Alterar um texto e verificar a atualização na página pública.

9. GESTÃO DE UTILIZADORES (apenas administrador)
   - Aceder a "Utilizadores" na sidebar.
   - Criar um novo utilizador, editar e desativar.
   - Verificar que perfis técnico e profissional não têm acesso a este módulo.

10. ALTERAÇÃO DE PASSWORD
    - No menu do utilizador (canto superior direito), clicar em
      "Alterar password".
    - Alterar a password e voltar a autenticar com a nova.

================================================================================
INFORMAÇÃO ADICIONAL
================================================================================

- O sistema implementa soft delete: para manter o histórico de registos, 
  equipamentos, localizações, documentos e fornecedores são desativados 
  sem ser eliminados da BD nem da listagem respetiva.

- Os ficheiros carregados pelos utilizadores (contratos, garantias e
  documentos em PDF) encontram-se na pasta public/uploads/.

- O histórico de commits está disponível no ficheiro commits.txt
  na raiz do projeto.

- Os registos de eventos estão na pasta private/logs

- O modelo DBML da base de dados encontra-se em bd/modelo.dbml. Os scripts 
  SQL para criação e população da base de dados estão também nesta pasta. 
  A base de dados incluí dados de teste já carregados, facilitando os testes.

- Estrutura de perfis:
    Administrador — acesso total a todos os módulos.
    Técnico       — acesso parcial no Dashboard, Restrito à Gestão de Conteúdos 
                    Públicos e Utilizadores. Não pode exportar. Só tem acesso a 
                    ativos. Não pode desativar nem reativar.
    Profissional  — acesso apenas a consulta e listagem de equipamentos. 

================================================================================