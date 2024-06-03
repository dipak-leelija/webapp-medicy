
const addTestAndSubTest = (t) => {
    let url;
    const modalContent = document.querySelector(".add-new-test-data-modal");
    const editNicheDetails = document.getElementById('editNicheDetails');
    const modalSizeId = document.getElementById('modal-sizeId');

    if (!modalContent || !editNicheDetails || !modalSizeId) {
        console.error("Required elements are not found in the DOM.");
        return;
    }

    if (t.id === 'add-testType') {
        url = 'ajax/add-labTestType.ajax.php';
        editNicheDetails.innerHTML = 'Add new test type';
        modalSizeId.classList.add('modal-lg');
        modalSizeId.classList.remove('modal-md');
        updateModalContent(modalContent, url, '380px');
        
    } else if (t.id === 'add-subTest') {
        url = 'ajax/add-subTest.ajax.php';
        editNicheDetails.innerHTML = 'Add subtest details';
        modalSizeId.classList.add('modal-lg');
        modalSizeId.classList.remove('modal-md');
        updateModalContent(modalContent, url, '500px');
    }
}

const updateModalContent = (modalContent, url, height) => {
    modalContent.innerHTML = `<iframe width="99%" height="${height}" frameborder="0" allowtransparency="true" src="${url}"></iframe>`;
}

