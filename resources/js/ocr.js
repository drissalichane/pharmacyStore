import Tesseract from 'tesseract.js';

// Force global exposure of Tesseract
window.Tesseract = Tesseract;
console.log('Tesseract exposed globally:', window.Tesseract);

window.performOCR = async function performOCR(imageUrl) {
    console.log('performOCR function triggered with imageUrl:', imageUrl);
    console.log('Image URL:', imageUrl); // Log the image URL
    const scannedWordsContainer = document.getElementById('scannedWords');
    if (!scannedWordsContainer) {
        console.error('Scanned Words container not found');
        return;
    }

    scannedWordsContainer.innerHTML = '<p>Scanning...</p>';

    try {
        Tesseract.recognize(
            imageUrl, // Directly pass the image URL
            'eng', // Language code for French
            {
                logger: info => console.log('Tesseract progress:', info) // Log progress
            }
        ).then(({ data: { text } }) => {
            console.log('OCR Result:', text);
            // Display the extracted text
            scannedWordsContainer.innerHTML = `<p>${text}</p>`;
        }).catch(error => {
            console.error('OCR Error:', error);
            scannedWordsContainer.innerHTML = '<p>Error scanning the image.</p>';
        });
    } catch (error) {
        console.error('Unexpected Error:', error);
        scannedWordsContainer.innerHTML = '<p>Unexpected error occurred.</p>';
    }
}

// Debugging: Log when the script is loaded
console.log('OCR script loaded successfully');
