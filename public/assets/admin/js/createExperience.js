    /**
     * Preview de Nombre - Apellido - Especialidad
     */

    const companyPreview = document.getElementById('company-preview')
    const specialtyPreview = document.getElementById('specialty-preview')
    const startPreview = document.getElementById('start-preview')
    const endPreview = document.getElementById('end-preview')
    const company = document.formExperience.company;
    const specialty = document.formExperience.specialty_es;
    const start = document.formExperience.start;
    const end = document.formExperience.end;
    //Cuando escriben en name
    company.addEventListener("keyup", ()=>{
        companyPreview.innerHTML = company.value;
    });
    //Cuando escriben en specialty
    specialty.addEventListener("keyup", ()=>{
        specialtyPreview.innerHTML = specialty.value;
    });
    //Cuando escriben en start
    start.addEventListener("keyup", ()=>{
        startPreview.innerHTML = start.value;
    });
    //Cuando escriben en end
    end.addEventListener("keyup", ()=>{
        endPreview.innerHTML = end.value;
    });


//Datemask dd/mm/yyyy para la fecha de inicio y fin
$('#start').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
$('#end').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })