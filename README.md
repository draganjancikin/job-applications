# Job Applications: D8 module assignment

U Drupalu 8 potrebno je napraviti modul koji će sadržati sledeće stvari:

* [x] Stranicu sa formom (slika se nalazi u prilogu), selectbox "Type" sadrži dve stavke Backend i Frontend. Prilikom selekcije Backend u Technology polju se pojavljuju opcije PHP i Java, a ukoliko se selektuje Frontend opcije u Technology selectbox-u su AngularJS i ReactJS.

* [x] Napraviti tabelu "job_applications" u bazi koja će sadržati polja sa forme: Name, Email, Type, Technology i Message.

* [x] Klikom na dugme Send, sa forme proveriti da li je validan format email-a, ukoliko nije vratiti poruku da se unese validan email.

* [x] Nakon submit-a forme poslati email sa podacima iz forme na site mail adresu i upisati podatke u tabelu "job_applications" koja je ranije napravljena.

* [x] Napraviti još jednu stranicu na kojoj će se nalaziti HTML tabela u kojoj će biti prikazani svi podaci iz "Job applications" tabele u bazi.

* [x] Ubaciti paginaciju na HTML tabelu koja će prikazivati 5 rezultata po stranici.

* [x] Podesiti da se na cron svakog ponedeljka u 8h ujutru šalje generički (Lorem Ipsum text) email svim korisnicima koji su submit-ovali formu u prethodnih 7 dana.

* [x] Bonus deo - Namestiti da se email-ovi šalju jedan po jedan kroz Queue worker.
