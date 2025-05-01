document.addEventListener('DOMContentLoaded', function() {
    /* const checkboxes = document.querySelectorAll('.cb-day');
    const selectedDaysInput = document.getElementById('input-days');

    const all = document.querySelector('.all');

    // Uncheck all checkbox when page loaded
    checkboxes.forEach(cb => {
        cb.checked = false;
    });
    all.checked = false;


    all.addEventListener('change', function (event){
        let selectedDays = [];
        checkboxes.forEach(cb => {
            cb.checked = !!event.target.checked;
            if(cb.checked){
                selectedDays.push(cb.value);
            }
        });
        selectedDaysInput.value = selectedDays.join(', ');
        console.log(selectedDays.toString())
    });

    //When checkbox are checked add day in list
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            let selectedDays = [];
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    selectedDays.push(cb.value);
                }
            });
            selectedDaysInput.value = selectedDays.join(',');
            console.log(selectedDays.toString())
        });
    }); */
});
