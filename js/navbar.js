    const tagContainer = document.getElementById('tagContainer');
    const leftArrow = document.getElementById('leftArrow');
    const rightArrow = document.getElementById('rightArrow');

    const scrollAmount = 120; // Ajuste selon la taille des tags

    leftArrow.addEventListener('click', () => {
        tagContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        updateArrows();
    });

    rightArrow.addEventListener('click', () => {
        tagContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        updateArrows();
    });

    function updateArrows() {
        setTimeout(() => {
            leftArrow.disabled = tagContainer.scrollLeft <= 0;
            rightArrow.disabled = tagContainer.scrollLeft + tagContainer.clientWidth >= tagContainer.scrollWidth;
        }, 200);
    }

    window.addEventListener('load', updateArrows);
    tagContainer.addEventListener('scroll', updateArrows);

    