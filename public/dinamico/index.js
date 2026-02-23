const columns = document.querySelectorAll('.footer-col');
document.getElementById('menu-toggle').addEventListener('click', function () {
  const menu = document.getElementById('mobile-menu'); menu.classList.toggle('show');
});
columns.forEach(column => {
  column.addEventListener('mouseenter', function () {
    this.style.transition = 'transform 0.3s ease';
    this.style.transform = 'translateY(-5px)';
  });

  column.addEventListener('mouseleave', function () {
    this.style.transform = 'translateY(0)';
  });
});
