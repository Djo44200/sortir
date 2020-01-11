# Sortir
Projet 2: Analyse, conception et développement d’un site d’organisation d'événements d’entreprises avec PHP et MySQL : définition de tests et d’outils de passage des tests.

1 - Installer PhpStorm + Plugins
2 - Installer WAMP 
3 - Installer CMDER
4 - Installer au minimum PHP 7.1.31 (Dossier WAMP => Extraire dans C:\wamp64\bin\php)
5 - Installer Composer (Developpeur => Choisir au minimum la version PHP 7.1.31 + Paramétrer le proxy)
6 - Installer Node
7 - Installer Yarn
8 - Installation de symfony flex: composer create-project symfony/skeleton "MonProjet" "3.4.*"
9 - Installation des modules
	=> composer req webserver
	=> composer req symfony/routing
	=> composer req annotations
	=> composer req --dev symfony/profiler-pack
	=> composer req annotation
	=> composer req twig
	=> composer req doctrine
	=> composer req maker
	=> composer req form
	=> composer req security-csrf
	=> composer req validator

OU 	=> composer require webserver symfony/routing annotations --dev symfony/profiler-pack annotation twig doctrine maker form security-csrf validator debug security

10 - Installation WebPack 
	=> yarn config set proxy http://10.100.0.248:8080
	=> composer require symfony/webpack-encore-bundle
	=> yarn install
	=> yarn add --dev @symfony/webpack-encore
	=> yarn add webpack-notifier --dev
	=> yarn add copy-webpack-plugin --dev
	=> Ajouter au fichier "webpack.config.js" le code suivant
var CopyWebpackPlugin = require('copy-webpack-plugin');
		.addPlugin(new CopyWebpackPlugin([
			{ from: './assets/images' to: 'images' }
		]))
	=> Utilisation de Webpack Encore (Ajout de la déclaration des scripts JS et CSS dans le fichier "webpack.config.js")
		=> Images: <img src="{{ asset('build/images/[Votre_Image.Extension]') }}" alt="Mon Image"/>
		=> CSS: {{ encore_entry_link_tag('Nom_Du_Fichier_Sans_Extension') }}
		=> JS: {{ encore_entry_script_tag('Nom_Du_Fichier_Sans_Extension') }}
		=> Compilation: yarn dev ou yarn watch

11 - php bin/console server:run