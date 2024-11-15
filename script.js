async function fetchData() {
    const response = await fetch('get_data.php'); // Endpoint PHP
    const data = await response.json();
    return data;
}

async function updateValues() {
    const data = await fetchData();

    // Atualiza a Taxa Geral
    setPercentage('general-progress', 'general-percentage-text', data.general);

    // Atualiza as Taxas por Módulo
    document.getElementById('php-percentage').innerText = `${data.modules.php}%`;
    document.getElementById('javascript-percentage').innerText = `${data.modules.javascript}%`;
    document.getElementById('python-percentage').innerText = `${data.modules.python}%`;
}

function setPercentage(circleId, textId, percentage) {
    const totalLength = 440; // Comprimento total do círculo
    const circle = document.getElementById(circleId);
    const text = document.getElementById(textId);

    const offset = totalLength - (percentage / 100) * totalLength;

    circle.style.strokeDashoffset = offset; // Atualiza o círculo
    text.innerText = `${percentage}%`; // Atualiza o texto
}

// Atualiza os valores ao carregar a página
updateValues();
