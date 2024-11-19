fetch('etecs.json') // Supondo que você tenha um arquivo chamado etecs.json
    .then(response => response.json())
    .then(etecs => {
        etecs.forEach(etec => {
            new mapboxgl.Marker()
                .setLngLat([etec.longitude, etec.latitude])
                .setPopup(new mapboxgl.Popup().setText(etec.name))
                .addTo(map);
        });
    })
    .catch(error => console.error('Erro ao carregar os dados das ETECs:', error));

const $list = document.querySelectorAll('li');
function activeLink() {
    $list.forEach(($li) => {
        $li.classList.remove('active');
    });
    this.classList.add('active');
}

$list.forEach(($li) => {
    $li.addEventListener('click', activeLink);
});