const lenis = new Lenis({
	wheelMultiplier: 1.1
});

function raf(time) {
	lenis.raf(time);
	requestAnimationFrame(raf);
}

requestAnimationFrame(raf);
