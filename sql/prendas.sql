USE superjogo;

-- Penalidades (50)
INSERT INTO
    prendas (descricao, tipo)
VALUES
    (
        'Cantar o hino nacional em voz alta',
        'penalidade'
    ),
    ('Dançar por 1 minuto sem música', 'penalidade'),
    ('Fazer 10 flexões', 'penalidade'),
    ('Contar uma piada para todos', 'penalidade'),
    (
        'Imitar um animal escolhido pelos outros times',
        'penalidade'
    ),
    (
        'Beber um copo de água sem usar as mãos',
        'penalidade'
    ),
    (
        'Fazer uma careta engraçada e manter por 10 segundos',
        'penalidade'
    ),
    (
        'Andar de costas até o final da sala',
        'penalidade'
    ),
    (
        'Falar um trava-línguas sem errar 3 vezes seguidas',
        'penalidade'
    ),
    ('Fazer uma imitação de um famoso', 'penalidade'),
    (
        'Recitar o alfabeto de trás para frente',
        'penalidade'
    ),
    (
        'Equilibrar um livro na cabeça por 30 segundos',
        'penalidade'
    ),
    (
        'Fazer um discurso de 1 minuto sobre um tema aleatório',
        'penalidade'
    ),
    (
        'Fingir ser uma estátua por 1 minuto',
        'penalidade'
    ),
    ('Escrever seu nome no ar com o pé', 'penalidade'),
    ('Cantar uma música infantil', 'penalidade'),
    (
        'Fazer um desenho com os olhos vendados',
        'penalidade'
    ),
    (
        'Dizer 5 qualidades suas em 10 segundos',
        'penalidade'
    ),
    ('Fazer um mini show de mágica', 'penalidade'),
    (
        'Contar uma história engraçada que aconteceu com você',
        'penalidade'
    ),
    (
        'Fazer uma declaração de amor para uma cadeira',
        'penalidade'
    ),
    ('Imitar um professor da escola', 'penalidade'),
    (
        'Fazer um rap improvisado sobre o jogo',
        'penalidade'
    ),
    (
        'Andar como um pinguim até o final da sala',
        'penalidade'
    ),
    (
        'Falar por 1 minuto sem usar a letra "A"',
        'penalidade'
    ),
    (
        'Fazer uma pose de super-herói e manter por 15 segundos',
        'penalidade'
    ),
    (
        'Cantar uma música em velocidade 2x',
        'penalidade'
    ),
    (
        'Fazer um comercial criativo de um objeto aleatório',
        'penalidade'
    ),
    ('Falar como um robô por 2 minutos', 'penalidade'),
    (
        'Fazer uma dramatização de uma cena de novela',
        'penalidade'
    ),
    ('Contar de 1 a 20 em outro idioma', 'penalidade'),
    (
        'Fazer um vozinha de personagem de desenho',
        'penalidade'
    ),
    ('Recitar um poema improvisado', 'penalidade'),
    (
        'Fazer uma dublagem engraçada de um vídeo mudo',
        'penalidade'
    ),
    (
        'Fingir ser um apresentador de TV por 1 minuto',
        'penalidade'
    ),
    (
        'Fazer um passo de dança inventado na hora',
        'penalidade'
    ),
    (
        'Descrever um objeto sem dizer seu nome ou função',
        'penalidade'
    ),
    (
        'Fazer uma entrevista imaginária com um objeto',
        'penalidade'
    ),
    (
        'Falar 3 mentiras e 1 verdade sobre você',
        'penalidade'
    ),
    (
        'Fazer um barulho de animal escolhido pelos outros',
        'penalidade'
    ),
    (
        'Cantar uma música só com a letra "lá"',
        'penalidade'
    ),
    (
        'Fazer um discurso motivacional para uma cenoura',
        'penalidade'
    ),
    (
        'Fingir ser um comentarista esportivo de um evento bobo',
        'penalidade'
    ),
    (
        'Fazer uma maquiagem criativa com caneta',
        'penalidade'
    ),
    (
        'Criar um slogan para o time adversário',
        'penalidade'
    ),
    (
        'Fazer um mini curso de algo que não sabe',
        'penalidade'
    ),
    (
        'Descrever como seria um dia na vida de uma formiga',
        'penalidade'
    ),
    (
        'Fazer um telejornal sobre coisas da sala',
        'penalidade'
    ),
    ('Cantar parabéns para você mesmo', 'penalidade'),
    (
        'Fazer um comercial de um produto inexistente',
        'penalidade'
    );

-- Bônus (50)
INSERT INTO
    prendas (descricao, tipo)
VALUES
    ('Escolher a próxima equipe a responder', 'bonus'),
    ('Ganhar 1 ponto extra', 'bonus'),
    ('Pular uma pergunta difícil', 'bonus'),
    (
        'Escolher a próxima prenda para outro time',
        'bonus'
    ),
    ('Trocar uma pergunta já vista', 'bonus'),
    (
        'Duplicar pontos na próxima resposta certa',
        'bonus'
    ),
    ('Escolher o tema da próxima pergunta', 'bonus'),
    (
        'Ganhar o direito de dar uma prenda a outro time',
        'bonus'
    ),
    (
        'Anular uma prenda recebida anteriormente',
        'bonus'
    ),
    (
        'Escolher dois membros do próximo time para responder',
        'bonus'
    ),
    ('Receber uma dica na próxima pergunta', 'bonus'),
    (
        'Converter uma resposta errada em certa',
        'bonus'
    ),
    (
        'Escolher quem vai responder no próximo time',
        'bonus'
    ),
    (
        'Ganhar imunidade contra prendas por 2 rodadas',
        'bonus'
    ),
    ('Roubar 1 ponto de outro time', 'bonus'),
    (
        'Escolher a dificuldade da próxima pergunta',
        'bonus'
    ),
    (
        'Ter direito a 2 respostas na próxima pergunta',
        'bonus'
    ),
    (
        'Cancelar a próxima prenda que receberia',
        'bonus'
    ),
    (
        'Ganhar o direito de ver a resposta antes de responder',
        'bonus'
    ),
    ('Transformar uma prenda em bônus', 'bonus'),
    (
        'Escolher um membro de outro time para fazer uma prenda',
        'bonus'
    ),
    (
        'Ganhar um coringa para usar quando quiser',
        'bonus'
    ),
    (
        'Converter uma resposta errada em prenda para outro time',
        'bonus'
    ),
    (
        'Escolher a ordem de resposta dos times',
        'bonus'
    ),
    (
        'Ganhar direito a uma segunda chance em caso de erro',
        'bonus'
    ),
    ('Fazer uma pergunta para outro time', 'bonus'),
    (
        'Escolher um tema que não vai cair nas perguntas',
        'bonus'
    ),
    (
        'Ganhar 50% de desconto na próxima penalidade',
        'bonus'
    ),
    (
        'Transformar uma penalidade em show de talentos',
        'bonus'
    ),
    (
        'Escolher a próxima música de fundo do jogo',
        'bonus'
    ),
    (
        'Ganhar direito de veto em uma pergunta',
        'bonus'
    ),
    (
        'Escolher o próximo apresentador do jogo',
        'bonus'
    ),
    (
        'Ganhar um ponto bônus para dividir como quiser',
        'bonus'
    ),
    (
        'Ter direito a consultar um membro do time na próxima resposta',
        'bonus'
    ),
    (
        'Escolher o formato da próxima pergunta',
        'bonus'
    ),
    (
        'Ganhar imunidade contra perda de pontos por 1 rodada',
        'bonus'
    ),
    (
        'Converter uma prenda em desafio para outro time',
        'bonus'
    ),
    (
        'Escolher quem aplica as prendas na próxima rodada',
        'bonus'
    ),
    (
        'Ganhar direito de inverter uma resposta certa/errada',
        'bonus'
    ),
    (
        'Escolher o próximo critério de pontuação',
        'bonus'
    ),
    (
        'Ganhar um "salve-se quem puder" para usar quando quiser',
        'bonus'
    ),
    (
        'Transformar uma pergunta difícil em fácil',
        'bonus'
    ),
    (
        'Escolher o próximo tema musical do jogo',
        'bonus'
    ),
    (
        'Ganhar o direito de "roubar" uma prenda de outro time',
        'bonus'
    ),
    (
        'Converter uma resposta certa em dobro de pontos',
        'bonus'
    ),
    (
        'Escolher o próximo modo de resposta (escrita, mímica, etc)',
        'bonus'
    ),
    (
        'Ganhar um "passe livre" para qualquer prenda futura',
        'bonus'
    ),
    ('Escolher o próximo juiz das respostas', 'bonus'),
    (
        'Ganhar direito a uma "dica de ouro" na próxima pergunta',
        'bonus'
    ),
    (
        'Transformar a próxima penalidade em bônus para seu time',
        'bonus'
    );

INSERT INTO
    prendas (descricao, tipo)
VALUES
    -- Bônus
    ('Ganhe 5 pontos extras', 'bonus'),
    ('Avance uma rodada sem responder', 'bonus'),
    (
        'Receba ajuda de um colega na próxima pergunta',
        'bonus'
    ),
    (
        'Troque a pergunta por outra mais fácil',
        'bonus'
    ),
    (
        'Dobre a pontuação da próxima resposta correta',
        'bonus'
    ),
    (
        'Escolha a equipe adversária para perder a vez',
        'bonus'
    ),
    ('Receba dica extra na próxima questão', 'bonus'),
    ('Ganhe 10 pontos imediatos', 'bonus'),
    ('Responda novamente se errar', 'bonus'),
    (
        'Escolha a próxima pergunta do adversário',
        'bonus'
    ),
    (
        'Proteção contra penalidade na próxima rodada',
        'bonus'
    ),
    ('Ganhe 3 pontos imediatos', 'bonus'),
    ('Troque de pergunta com outra equipe', 'bonus'),
    (
        'Gire novamente e ganhe prêmio cumulativo',
        'bonus'
    ),
    ('Use duas respostas na mesma pergunta', 'bonus'),
    ('Anule a última penalidade recebida', 'bonus'),
    ('Ganhe o dobro de pontos nesta rodada', 'bonus'),
    (
        'Obtenha carta de “passe livre” para penalidade',
        'bonus'
    ),
    ('Conquiste mais 7 pontos', 'bonus'),
    (
        'Escolha entre três perguntas para responder',
        'bonus'
    ),
    ('Ganhe 1 ponto extra', 'bonus'),
    ('Ganhe 2 pontos extras', 'bonus'),
    ('Ganhe 4 pontos extras', 'bonus'),
    ('Ganhe 6 pontos extras', 'bonus'),
    ('Ganhe 8 pontos extras', 'bonus'),
    ('Ganhe 9 pontos extras', 'bonus'),
    ('Ganhe 12 pontos extras', 'bonus'),
    ('Ganhe 15 pontos extras', 'bonus'),
    ('Ganhe 20 pontos extras', 'bonus'),
    (
        'Troque a vez com um jogador à sua escolha',
        'bonus'
    ),
    (
        'Ganhe proteção de 2 rodadas contra penalidades',
        'bonus'
    ),
    (
        'Troque a sua pergunta por uma de nível fácil',
        'bonus'
    ),
    ('Ganhe mais 5 segundos para responder', 'bonus'),
    (
        'Permita que sua equipe escolha quem responde',
        'bonus'
    ),
    (
        'Ganhe o triplo de pontos se acertar a próxima',
        'bonus'
    ),
    ('Receba uma dica bônus da banca', 'bonus'),
    (
        'Ganhe a chance de corrigir sua última resposta',
        'bonus'
    ),
    ('Ganhe 50 pontos instantâneos', 'bonus'),
    (
        'Ganhe mais tempo para pensar na próxima pergunta',
        'bonus'
    ),
    (
        'Escolha o próximo adversário a responder',
        'bonus'
    ),
    (
        'Ganhe imunidade até sua próxima jogada',
        'bonus'
    ),
    (
        'Ganhe um “coringa” para usar quando quiser',
        'bonus'
    ),
    ('Troque de lugar com outro jogador', 'bonus'),
    ('Ganhe pontuação igual ao líder', 'bonus'),
    (
        'Ganhe o mesmo número de pontos da última rodada',
        'bonus'
    ),
    (
        'Multiplique por 2 os pontos da próxima resposta',
        'bonus'
    ),
    ('Ganhe 30 pontos extras', 'bonus'),
    ('Ganhe a vez de jogar imediatamente', 'bonus'),
    (
        'Escolha um adversário para perder pontos',
        'bonus'
    ),
    -- Penalidades
    ('Perda de 5 pontos', 'penalidade'),
    ('Pule a próxima rodada', 'penalidade'),
    (
        'Dobre a pontuação do adversário na próxima',
        'penalidade'
    ),
    (
        'Responda uma pergunta difícil obrigatória',
        'penalidade'
    ),
    ('Perda de 10 pontos', 'penalidade'),
    ('Troque sua vez com um adversário', 'penalidade'),
    ('Responda sem ajuda de sua equipe', 'penalidade'),
    ('Perda de 3 pontos', 'penalidade'),
    ('Perda de 7 pontos', 'penalidade'),
    ('Perda de 15 pontos', 'penalidade'),
    (
        'Passe a vez para a equipe à esquerda',
        'penalidade'
    ),
    (
        'Passe a vez para a equipe à direita',
        'penalidade'
    ),
    ('Responda em 5 segundos', 'penalidade'),
    (
        'Responda sem opções de múltipla escolha',
        'penalidade'
    ),
    ('Perda de 2 pontos', 'penalidade'),
    ('Perda de 4 pontos', 'penalidade'),
    ('Perda de 6 pontos', 'penalidade'),
    ('Perda de 8 pontos', 'penalidade'),
    ('Perda de 9 pontos', 'penalidade'),
    ('Perda de 12 pontos', 'penalidade'),
    (
        'Responda uma pergunta sobre tema escolhido pelo adversário',
        'penalidade'
    ),
    ('Perca 1 ponto', 'penalidade'),
    ('Perca 20 pontos', 'penalidade'),
    (
        'Troque sua pontuação com o último colocado',
        'penalidade'
    ),
    (
        'Jogue de olhos vendados (metaforicamente)',
        'penalidade'
    ),
    ('Perda de 50 pontos', 'penalidade'),
    ('Fique 2 rodadas sem jogar', 'penalidade'),
    (
        'Responda uma pergunta de nível difícil',
        'penalidade'
    ),
    ('Responda duas perguntas seguidas', 'penalidade'),
    (
        'Se errar, perca o dobro de pontos',
        'penalidade'
    ),
    (
        'Não pode receber dicas nesta rodada',
        'penalidade'
    ),
    (
        'Troque sua pergunta por uma mais difícil',
        'penalidade'
    ),
    ('Perda de 25 pontos', 'penalidade'),
    (
        'Responda na língua escolhida pelo adversário',
        'penalidade'
    ),
    ('Passe a vez obrigatoriamente', 'penalidade'),
    ('Adversário escolhe sua pergunta', 'penalidade'),
    (
        'Troque de lugar com último colocado',
        'penalidade'
    ),
    ('Perda de 18 pontos', 'penalidade'),
    (
        'Perca todos os pontos desta rodada',
        'penalidade'
    ),
    ('Responda sem tempo para pensar', 'penalidade'),
    (
        'Responda com metade do tempo normal',
        'penalidade'
    ),
    (
        'Escolha um aliado para perder pontos',
        'penalidade'
    ),
    (
        'Perca metade dos seus pontos totais',
        'penalidade'
    ),
    ('Responda uma pergunta relâmpago', 'penalidade'),
    ('Perda de 40 pontos', 'penalidade'),
    (
        'Fique 3 rodadas sem ganhar pontos',
        'penalidade'
    ),
    ('Responda com penalidade de tempo', 'penalidade'),
    ('Perda de 35 pontos', 'penalidade');