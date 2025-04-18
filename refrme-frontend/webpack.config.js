const { VueLoaderPlugin } = require('vue-loader');

module.exports = {
  // Your webpack configuration
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: {
          compilerOptions: {
            isCustomElement: tag => tag.startsWith('swiper-')
          }
        }
      }
    ]
  },
  plugins: [
    new VueLoaderPlugin(),
  ]
}; 