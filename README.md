# StonksBay
## Version Évènement de rentrée 2023

## URL : https://www.stonks-me.duckdns.org
## Logiciels et versions :
- Un serveur web (Nignx/Apache)
- PHP (minimum : 7.3.0 / utilisée : 7.4.28)
- MariaDB/MySQL
## Installation :
1. Clonez le dépôt à la racine de votre serveur web
2. Créez une base de données appelée "stonksbay" et attribuée lui un utilisateur
3. Renseignez les identifiants précédants dans le fichier `php/modules/init_bdd.php`
4. Inscrivez toutes les tables dans votre base de données
5. Remplissez le fichier `api_tokens.json` selon ses attributs et assurez-vous que PHP possède les droits de lecture et d'écriture dessus:
	- "HCAPTCHA_KEY" : représente la clé permettant d'accéder à l'API de [hCaptcha](https://www.hcaptcha.com/ "hCaptcha")
	- "RAPIDAPI_KEY" : représente la clé permettant d'accéder aux APIs nécessaire pour récuperer les prix
		1. Créez vous un compte sur [RapidAPI](https://rapidapi.com "RapidAPI")
		2. Abonnez-vous aux APIs suivantes (version gratuite) :
			- https://rapidapi.com/JSL346/api/amazon-product-price-data
			- https://rapidapi.com/rene.meuselwitz/api/ali-express1
			- https://rapidapi.com/vladdnepr1989/api/aliexpress-unofficial
			- https://rapidapi.com/lancerhe/api/aliexpress19
6. Assurez-vous enfin que PHP possède bien les droits de lecture sur le fichier `tokens/token_cdiscount.txt`
