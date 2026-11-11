var currentStep = 1;

function showStep(step) {
    document.querySelectorAll('.step').forEach(function(s) { s.classList.remove('active'); });
    document.getElementById('step' + step).classList.add('active');
}

function clearErrors() {
    document.querySelectorAll('.error-message').forEach(function(e) { e.innerText = ''; });
    document.querySelectorAll('input, textarea, select').forEach(function(el) { el.classList.remove('error'); });
}

function validateStep1() {
    clearErrors();
    var valid = true;
    var title = document.getElementById('title');
    var desc = document.getElementById('description');
    if (title.value.trim() === '') {
        document.getElementById('titleError').innerText = 'Title is required';
        title.classList.add('error'); valid = false;
    }
    if (desc.value.trim() === '') {
        document.getElementById('descError').innerText = 'Description is required';
        desc.classList.add('error'); valid = false;
    }
    if (valid) nextStep();
}

function validateStep2() {
    clearErrors();
    var details = document.getElementById('details');
    if (details.value.trim() === '') {
        document.getElementById('detailsError').innerText = 'Details are required';
        details.classList.add('error'); return;
    }
    nextStep();
}

function postTask() {
    clearErrors();
    var valid = true;
    function check(id, errorId, msg) {
        var el = document.getElementById(id);
        if (!el.value) {
            document.getElementById(errorId).innerText = msg;
            el.classList.add('error'); valid = false;
        }
    }
    check('requirement', 'reqError', 'Required');
    check('platform', 'platformError', 'Select platform');
    check('datetime', 'dateError', 'Select date/time');
    check('paymentType', 'paymentError', 'Select payment');
    check('duration', 'durationError', 'Enter duration');
    check('amount', 'amountError', 'Enter amount');

    if (valid) {
        // Salin nilai step 1 & 2 ke hidden fields
        document.getElementById('titleHidden').value = document.getElementById('title').value;
        document.getElementById('descHidden').value = document.getElementById('description').value;
        document.getElementById('detailsHidden').value = document.getElementById('details').value;
        document.getElementById('jobForm').submit();
    }
}

function nextStep() { currentStep++; showStep(currentStep); }
function prevStep() { currentStep--; showStep(currentStep); }
function cancelForm() {
    if (confirm('Are you sure you want to cancel?')) {
        document.querySelectorAll('input, textarea').forEach(function(el) { el.value = ''; });
        document.querySelectorAll('select').forEach(function(el) { el.selectedIndex = 0; });
        clearErrors(); currentStep = 1; showStep(1);
    }
}