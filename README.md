# skrupel_revamped
Fully Revamped Version of Skrupel (c) by Bernd Kantoks

Leider wzrde die Entwicklung wohl aufgegeben und das Spiel ist in der Original als auch in der Spezialversion (Tribute Compilation) nicht mit der aktuellen PHP Version kompatibel.

Es handelt sich um eine sehr große Menge an Quellcode der überarbeitet werden muß.

Besonderes Augenmerk liegt hier auf die Optimierung der SQL Abfragen und an die Anpassung an adoDB.
Indizes wurde hinzugefügt um ein ständiges Fulltablescan zu minimieren. Diese Indizes werden bei SELECT Abfragen aktiv genutzt.

Dieses Skript befindet sich noch aktiv in der Umbauphase und ist so noch nicht nutzbar.

Wenn es denn soweit ist die config-dist.php im Ordner includes umbenennen zu config.php und anpassen und im Anschluß das SQL in eine mysql Datenbank importieren.

Dann kann es losgehen. ;)

Weitere informationen folgen in Kürze.

Benötigte Zusatzsoftware :
Smarty 5.4.x
ADOdb 5.22.7 

beides is bereits enthalten im Ordner libs /

Php in der Version 8.2.x

Derzeit wird auf einem Apache Webserver entwickelt.  

MySql in der aktuellsten Version.

Am besten legt ihr eine Datenbank mit PHPMyadmin an und importiert das sql File.
ich weiß nur nicht wann ich dazu kommen werde den Adminbereich anzufangen, denn leider ist hier ebenfalls sehr viel anzupassen.
Ich habe bis dato noch nicht herausgefunden, weshalb dort mit FTP Daten gearbeitet wird. Werde ich aber noch rausfinden. :-)


Anpassung des Installscriptes wird ganz zum Schluß durchgeführt.

In dieser Version ist übrigens möglich Benutzer zu bearbeiten also auch ein neues Passwort zu vergeben. 
War allem anschein nach nicht integriert denn die Buttons im Adminbereich beim Benutzer waren ohne Funktion.


Wenn sich jemand beteiligen möchte, kann er dies gerne tun. Freue mich über helfende Hände ;)
