// -- Remove Button Block Styles
// -------------------------------------------- */

// wp.domReady( () => {

  // // -- Remove button styles
  // wp.blocks.unregisterBlockStyle( 'core/button', 'fill' );
  // wp.blocks.unregisterBlockStyle( 'core/button', 'outline' );

  // // -- Add button styles
  // wp.blocks.registerBlockStyle( 'core/button', {
  //   name: 'default',
  //   label: 'Default',
  //   isDefault: true,
  // } );

  // wp.blocks.registerBlockStyle( 'core/button', {
  //   name: 'full',
  //   label: 'Full',
  //   isDefault: true,
  // } );

// -- Add custom class to HTML when sidebar is opened
// if ( ! $( '.edit-post-layout' ).hasClass('is-sidebar-opened') ) {
//   $('html').toggleClass('js-sidebar-opened')
// }

// Add custom attributes to all blocks, NOT WORKING
// function add_custom_attributes( settings, name ) {
//   if ( settings.attributes ) {
//     settings.attributes.customAttribute = {
//       type: 'string',
//       default: ''
//     };
//     settings.attributes.anotherCustomAttribute = {
//       type: 'boolean',
//       default: false
//     };
//   }
//   return settings;
// }

// wp.hooks.addFilter( 'blocks.registerBlockType', 'namespace/filterBlockAttributes', add_custom_attributes );