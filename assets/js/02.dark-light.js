  const toggleBtn = document.getElementById('theme-toggle');
  const body = document.body;

  // Load saved mode
  if (localStorage.getItem('theme') === 'light') {
    body.classList.add('light-mode');
	toggleBtn.classList.add('light');
  }

  toggleBtn.addEventListener('click', () => {
    body.classList.toggle('light-mode');
    const isLight = body.classList.contains('light-mode');

	if(isLight){toggleBtn.classList.add('light');}
	else {toggleBtn.classList.remove('light');}

    
    localStorage.setItem('theme', isLight ? 'light' : 'dark');
  });