# SuperJogo - Sistema de Quiz Interativo

## 📝 Descrição

O SuperJogo é um sistema de quiz interativo desenvolvido para criar competições entre equipes de forma dinâmica e divertida. O sistema permite:

- Cadastro de equipes com múltiplos membros
- Configuração personalizada do número de perguntas
- Sorteio automático de equipes e membros para responder
- Sistema de pontuação com bônus e penalidades
- Ranking final com animações e visualização de desempenho

## 🛠️ Tecnologias Utilizadas

- **Front-end**: HTML5, CSS3, JavaScript (ES6+), Bootstrap 5, Font Awesome
- **Back-end**: PHP 7.4+
- **Banco de Dados**: MySQL
- **Padrões de Projeto**: Singleton (para conexão com banco de dados), MVC (estrutura básica)

## 📋 Requisitos do Sistema

- Servidor web (Apache, Nginx)
- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Composer (para possíveis dependências futuras)

## 🚀 Instalação

1. **Clone o repositório**:

   ```bash
   git clone https://github.com/seu-usuario/SuperJogo.git
   cd SuperJogo
   ```

2. **Configure o banco de dados**:

   - Importe os arquivos SQL na ordem:
     ```bash
     mysql -u usuario -p superjogo < sql/database.sql
     mysql -u usuario -p superjogo < sql/prendas.sql
     mysql -u usuario -p superjogo < sql/perguntas.sql
     ```

3. **Configure as credenciais do banco**:

   - Edite o arquivo `config/db.php` com suas credenciais de banco de dados.

4. **Configuração do servidor**:
   - Configure seu servidor web para apontar para a pasta do projeto.

## 🎮 Como Jogar

1. **Cadastro de Equipes**:

   - Acesse a página inicial (`index.php`)
   - Cadastre pelo menos 2 equipes com seus respectivos membros

2. **Iniciar o Jogo**:

   - Após cadastrar as equipes, defina o número de perguntas
   - Clique em "Iniciar Jogo"

3. **Dinâmica do Jogo**:

   - O sistema sorteará uma equipe e um membro para responder
   - Cada pergunta acertada vale 1 ponto
   - Respostas erradas resultam em penalidades
   - Bônus são concedidos para respostas corretas

4. **Finalização**:
   - Quando todas as perguntas forem respondidas, o ranking final é exibido
   - Você pode reiniciar o jogo ou voltar ao início

## 📂 Estrutura de Arquivos

```
SuperJogo/
├── assets/                  # Arquivos estáticos
│   ├── css/                 # Folhas de estilo
│   └── js/                  # Scripts JavaScript
├── config/                  # Configurações do sistema
│   ├── db.php               # Configuração do banco de dados
│   └── functions.php        # Funções auxiliares
├── sql/                     # Scripts SQL
│   ├── database.sql         # Estrutura do banco
│   ├── perguntas.sql        # Perguntas padrão
│   └── prendas.sql          # Bônus e penalidades
├── clear_all.php            # Limpa todos os dados
├── game.php                 # Página principal do jogo
├── get_teams.php            # API para listar equipes
├── index.php                # Página inicial
├── process_team.php         # Processa cadastro/remoção de equipes
├── ranking.php              # Exibe ranking final
├── reset_game.php           # Reinicia o jogo mantendo equipes
└── start_game.php           # Inicializa uma nova partida
```

## 🛠️ Funcionalidades Principais

- **Cadastro Dinâmico de Equipes**:

  - Adição/remoção de membros em tempo real
  - Validação de formulários
  - Notificações de feedback

- **Sistema de Jogo**:

  - Sorteio aleatório de perguntas
  - Rodadas alternadas entre equipes
  - Histórico completo das respostas

- **Ranking Interativo**:
  - Animação de destaque para o campeão
  - Tooltips com detalhes dos pontos
  - Efeitos visuais para melhor experiência

## 🔧 Personalização

Você pode personalizar o jogo de várias formas:

1. **Perguntas**:

   - Adicione novas perguntas no arquivo `sql/perguntas.sql`

2. **Bônus/Penalidades**:

   - Edite o arquivo `sql/prendas.sql` para adicionar novas prendas

3. **Estilo Visual**:
   - Modifique os arquivos CSS em `assets/css/`
