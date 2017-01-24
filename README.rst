MailMojo WordPress Widget
=========================

This is a WordPress plugin for MailMojo_, a Norwegian email marketing
webapp. The rest of this document is in Norwegian, see README.txt or
this plugin's `WordPress plugin page`_ for English information.

.. _MailMojo: https://mailmojo.no/
.. _WordPress plugin page: http://wordpress.org/extend/plugins/mailmojo-widget/

Beskrivelse
-----------

Dette er en WordPress widget som gjør det enkelt å legge til et
påmeldingsskjema til en MailMojo e-postliste på ditt nettsted.

*Utvidelsen krever en MailMojo-konto.* Du kan opprette en gratis
MailMojo-konto her_.

.. _her: https://mailmojo.no/registrering

Systemkrav
----------

- WordPress 4.0.0 eller nyere
- PHP 5.4+ med cURL-modulen

Installasjon
------------

Utvidelsen kan automatisk installeres direkte i din
WordPress-installasjon ved å gå til «Utvidelser > Legg til nytt» og søke
opp «MailMojo». Finn «MailMojo Widget» i listen og klikk «Installer nå».
Husk å aktiver utvidelsen etter du har installert den, ved å gå til
«Utvidelser»-menyen og trykk «Aktiver» på «MailMojo Widget».

Koden som ligger på Github kan i enkelte tilfeller være nyere, så du kan
alternativt installere utvidelsen manuelt. Klon eller last ned repoet via
den grønne knappen oppe i høyre hjørnet. Deretter kjør kommandoen
`cd src && composer install`. Last så opp innholdet i src/ til
/wp-content/plugins/mailmojo-widget/ i WordPress-installasjonen din og aktiver
den gjennom «Utvidelser»-menyen i WordPress admin.

Aktivere påmeldingsskjema
~~~~~~~~~~~~~~~~~~~~~~~~~

Når utvidelsen er installert kan du aktivere et påmeldingsskjema.

1. Fyll ut manglende innstillinger under «Innstillinger > MailMojo»
2. Dra og slipp widgeten til den plassen du ønsker under «Utseende >
   Widgeter»
3. Velg e-postliste og gjør eventuelt andre endringer du ønsker
