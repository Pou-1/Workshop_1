INSERT INTO articles (id, titre, resumee, tags, contenu, auteurs, date_publication, img_principale) VALUES
(1,
 'Nouvelle espèce découverte !',
 'Une nouvelle espèce de primate découverte dans une région isolée de Chine.',
 '1,2,3',
 'En Chine méridionale, une grotte vient d\'être découverte contenant les restes d\'une espèce inconnue de primate. Les chercheurs estiment que cette découverte pourrait bouleverser notre compréhension de l\'évolution.',
 '1,2',
 '2025-09-14',
 'images/primate.jpg'),

(2, 
 'Les avancées de l\'IA en médecine',
 'Des chercheurs utilisent l\'IA pour prédire les maladies avant leur apparition.',
 '2,4',
 'Grâce à l\'apprentissage automatique, des algorithmes permettent de détecter les premiers signes de certaines maladies chroniques, ouvrant la voie à une prévention plus efficace.',
 '3',
 '2025-09-10',
 'images/ia_medecine.jpg'),

(3, 
 'Climat : records de chaleur',
 'L\'été 2025 a battu de nouveaux records de température dans plusieurs pays.',
 '3,5',
 'Selon l\'Organisation météorologique mondiale, les vagues de chaleur se multiplient et s\'intensifient, causant des problèmes sanitaires et environnementaux.',
 '2,4',
 '2025-08-22',
 'images/climat.jpg'),

(4,
 'Découverte d\'exoplanètes',
 'La NASA annonce la découverte de trois nouvelles exoplanètes potentiellement habitables.',
 '1,4,5',
 'Les télescopes spatiaux ont permis d\'identifier plusieurs planètes situées dans la zone habitable de leur étoile. Les chercheurs étudient leurs atmosphères afin de détecter des signes de vie.',
 '5',
 '2025-09-01',
 'images/exoplanetes.jpg'),

(5,
 'Énergies renouvelables : progrès',
 'Une nouvelle génération de panneaux solaires atteint un rendement record.',
 '2,3',
 'Une startup européenne a mis au point une technologie solaire capable de produire 40% d\'énergie en plus que les panneaux classiques, ouvrant des perspectives prometteuses.',
 '1,3',
 '2025-09-12',
 'images/solaire.jpg');


INSERT INTO tags (id, nom) VALUES
(1, 'Découverte'),
(2, 'Science'),
(3, 'Environnement'),
(4, 'Technologie'),
(5, 'Espace');

INSERT INTO auteurs (id, nom, prenom) VALUES
(1, 'Durand', 'Alice'),
(2, 'Martin', 'Julien'),
(3, 'Nguyen', 'Sophie'),
(4, 'Kouassi', 'Amadou'),
(5, 'Rodriguez', 'Carlos');
