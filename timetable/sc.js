// Sample timetable images (replace with your actual image paths)
const timetableImages = {
    '1l1': 'https://via.placeholder.com/800x600?text=استعمال+زمن+السنة+الأولى',
    '1l1': 'https://via.placeholder.com/800x600?text=استعمال+زمن+السنة+الأولى',
    '1l1': 'https://via.placeholder.com/800x600?text=استعمال+زمن+السنة+الأولى',
    '1l1': 'https://via.placeholder.com/800x600?text=استعمال+زمن+السنة+الأولى',


};

document.getElementById('timetableForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const selectedClass = document.getElementById('class').value;
    
    if (selectedClass) {
        document.getElementById('timetableTitle').textContent = `استعمال زمن ${selectedClass}`;
        document.getElementById('timetableImage').src = timetableImages[selectedClass];
        document.getElementById('timetableImage').alt = `استعمال زمن ${selectedClass}`;
        const today = new Date();
        document.getElementById('updateDate').textContent = today.toLocaleDateString('ar-EG');
        document.getElementById('timetableDisplay').style.display = 'block';
    }
});

let currentScale = 1;
const timetableImage = document.getElementById('timetableImage');

document.getElementById('zoomInBtn').addEventListener('click', function() {
    currentScale += 0.1;
    timetableImage.style.transform = `scale(${currentScale})`;
});

document.getElementById('zoomOutBtn').addEventListener('click', function() {
    if (currentScale > 0.5) {
        currentScale -= 0.1;
        timetableImage.style.transform = `scale(${currentScale})`;
    }
});
document.getElementById('class').addEventListener('change', function() {
    if (this.value) {
        document.getElementById('timetableForm').dispatchEvent(new Event('submit'));
    }
});