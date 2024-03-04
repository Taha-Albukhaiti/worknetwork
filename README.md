# WorkNetwork

WorkNetwork ist eine Webanwendung, die entwickelt wurde, um eine Plattform für Fachleute verschiedener Branchen zu
bieten, um sich zu vernetzen, Stellenangebote zu finden und mit potenziellen Arbeitgebern oder Mitarbeitern in Kontakt
zu treten.

## Features

- **Benutzeranmeldung und -verwaltung**: Benutzer können sich registrieren, anmelden und ihre Profile verwalten.

- **Profilverwaltung**: Benutzer können ihre Profile erstellen, bearbeiten und löschen, einschließlich persönlicher
  Informationen, Arbeitsgeschichte und Portfolio.

- **Stellenangebote und -suche**: Benutzer können Stellenangebote durchsuchen, filtern und sich bewerben.

- **Netzwerkfunktionen**: Benutzer können sich mit anderen Fachleuten vernetzen, Nachrichten senden und empfangen, und
  Kontakte verwalten.

- **Admin-Panel**: Administratoren können Benutzer, Stellenangebote und andere Inhalte verwalten.

- **Responsive Design**: Die Anwendung ist für die Verwendung auf verschiedenen Geräten und Bildschirmgrößen optimiert.


## Installation

1. Klone das Repository auf deinem lokalen System:

    ```bash 
   git clone git@github.com:Taha-Albukhaiti/worknetwork.git
   ```

2. Wechsle in das Projektverzeichnis:

   ```bash 
    cd worknetwork
    ```

3. Installiere die Abhängigkeiten mit Composer:

   ```bash 
        composer install
   ```
4. Kopiere die Umgebungsvariablen:

   ```bash 
    cp .env.example .env
   ```
5. Generiere den Anwendungsschlüssel:

   ```bash 
    php artisan key:generate
    ```
6. Führe die Datenbankmigrationen aus:

   ```bash 
    php artisan migrate
    ```
7. Starte den Entwicklungsserver:
8. 
   ```bash 
    php artisan serve
    ```
9. Öffne die Anwendung in deinem Browser:

   ```bash 
    http://localhost:8000
    ```

## Technologien

- **Laravel**: Das Projekt basiert auf dem Laravel-Framework, das eine schnelle und effiziente Entwicklung von Webanwendungen ermöglicht.

- **PHP**: Die serverseitige Logik der Anwendung wird mit PHP geschrieben, einer weit verbreiteten Programmiersprache für Webentwicklung.

- **MySQL**: Die Daten werden in einer MySQL-Datenbank gespeichert, einem weit verbreiteten relationalen Datenbankmanagementsystem.

- **HTML, CSS, JavaScript**: Für die Benutzeroberfläche und das Frontend-Design werden die Grundlagen von HTML, CSS und JavaScript verwendet.

## Beitrag

Beiträge sind willkommen! Wenn du Vorschläge für neue Funktionen hast, Fehler gefunden hast oder einfach nur Feedback geben möchtest, bitte öffne ein Issue oder einen Pull-Request.
