window.addEvent('domready', function () {
    Sexy = new SexyAlertBox();
});

function potwierdzenie(sciezka, komunikat) {
    Sexy.confirm(komunikat, {
        textBoxBtnOk: 'Tak',
        textBoxBtnCancel: 'Nie',
        onComplete:
            function (returnvalue) {
                if (returnvalue) {
                    window.location.replace(sciezka);
                }
            }
    });
};