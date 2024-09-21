const images = [
    'image1.jpg',  
    'image2.jpg',
    'image3.jpg'
];

let currentImageIndex = 0;

function changeBackgroundImage() {
    currentImageIndex = (currentImageIndex + 1) % images.length;
    document.body.style.backgroundImage = `url('${images[currentImageIndex]}')`;
}

// Change background image every 2 seconds
setInterval(changeBackgroundImage, 2000);

// Initial call to set the first background image
changeBackgroundImage();
