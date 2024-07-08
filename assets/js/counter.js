function animateStats() {
  const stats = document.querySelectorAll('.csc_blogging_locations-stat');

  stats.forEach(stat => {
      const updateCount = () => {
          const target = +stat.getAttribute('data-target');
          const count = +stat.innerText.replace(/,/g, ''); // Remove commas for accurate parsing
          const increment = target / 100;

          if (count < target) {
              // Format the number with commas as thousands separators before setting it as innerText
              stat.innerText = Math.ceil(count + increment).toLocaleString('en-US');
              setTimeout(updateCount, 30);
          } else {
              // Ensure the final number is also formatted
              stat.innerText = target.toLocaleString('en-US');
          }
      };

      updateCount();
  });
}

document.addEventListener('DOMContentLoaded', function() {
  const observer = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
          if (entry.isIntersecting) {
              animateStats();
              observer.unobserve(entry.target);
          }
      });
  }, {threshold: 0.5});

  const statsSection = document.querySelector('.csc_blogging_locations-flex__stat__container');
  observer.observe(statsSection);
});