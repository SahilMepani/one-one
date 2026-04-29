import Swiper from 'swiper';
import {
	Navigation,
	Pagination,
	Autoplay,
	FreeMode,
	Scrollbar,
	A11y,
	EffectFade,
	// EffectCreative,
	Thumbs
	// Keyboard,
	// Mousewheel,
	// EffectCoverflow,
	// EffectCube,
	// EffectFlip,
	// EffectCards,
	// Grid,
	// HashNavigation,
	// History,
	// Controller,
	// Manipulation,
	// Parallax,
	// Virtual,
	// Zoom,
} from 'swiper/modules';

// Initialize Swiper with modules
Swiper.use([
	Navigation,
	Pagination,
	Autoplay,
	FreeMode,
	Scrollbar,
	A11y,
	EffectFade,
	// EffectCreative,
	Thumbs
	// Keyboard,
	// Mousewheel,
	// EffectCoverflow,
	// EffectCube,
	// EffectFlip,
	// EffectCards,
	// Grid,
	// HashNavigation,
	// History,
	// Controller,
	// Manipulation,
	// Parallax,
	// Virtual,
	// Zoom,
]);

// Make Swiper available globally for other scripts
window.Swiper = Swiper;
