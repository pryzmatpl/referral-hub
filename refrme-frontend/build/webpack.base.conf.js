const path = require("path");
const utils = require("./utils");
const webpack = require("webpack");
const vueLoaderConfig = require('./vue-loader.conf')
const { VueLoaderPlugin } = require('vue-loader');
const HtmlWebpackPlugin = require("html-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");
const Dotenv = require("dotenv-webpack");
const CopyWebpackPlugin = require('copy-webpack-plugin');
const buildAssetsRoot = path.resolve(__dirname, "../dist");
const dotenv = require('dotenv').config();

console.log(process.env)

const { definitions } = new Dotenv({
  path: path.resolve(__dirname, '.env'), // load this now instead of the ones in '.env'
  safe: true, // load '.env.example' to verify the '.env' variables are all set. Can also be a string to a different file.
  allowEmptyValues: false, // allow empty variables (e.g. `FOO=`) (treat it as empty string, rather than missing)
  systemvars: false, // load all the predefined 'process.env' variables which will trump anything local per dotenv specs.
  silent: false, // hide any errors
  defaults: false, // load '.env.defaults' as the default values if empty.
  prefix: 'VUE_APP_', // Only include environment variables that start with VUE_APP_
});

module.exports = {
  cache: false,
  context: path.resolve(__dirname, "../"),
  entry: {
    app: path.resolve(__dirname, "../src/index.js")
  },
  output: {
    path: buildAssetsRoot,
    filename: utils.assetsPath("js/[name].[contenthash].js"),
    chunkFilename: utils.assetsPath("js/[name].[contenthash].js"),
    publicPath: "/",  // Use '/' or another valid URL path
    clean: true,
  },
  resolve: {
    extensions: [".js", ".vue", ".json"],
    alias: {
      "vue$": "vue/dist/vue.esm-bundler.js",
      "@": path.resolve(__dirname, "../src"),
      "process": require.resolve("process")
    },
    fallback: {
      process: require.resolve("process"),
      path: require.resolve("path-browserify"),
      stream: require.resolve("stream-browserify"),
      util: require.resolve("util/"),
      buffer: require.resolve("buffer/")
    }
  },
  optimization: {
    splitChunks: {
      chunks: "all",
    },
    minimize: true,
    minimizer: [
      new TerserPlugin(),
      new CssMinimizerPlugin(),
    ],
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: "vue-loader",
        options: {
          ...vueLoaderConfig,
          compilerOptions: {
            isCustomElement: tag => tag.startsWith('swiper-')
          }
        }
      },
      {
        test: /\.js$/,
        loader: 'babel-loader',
        exclude: /node_modules/,
        include: [path.resolve(__dirname, '../src'), path.resolve(__dirname, '../test')],
      },
      {
        test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
        type: 'asset',
        parser: {
          dataUrlCondition: {
            maxSize: 10 * 1024 // 10kb
          }
        },
        generator: {
          filename: utils.assetsPath("img/[name].[hash:7][ext]")
        }
      },
      {
        test: /\.(mp4|webm|ogg|mp3|wav|flac|aac)(\?.*)?$/,
        type: 'asset',
        parser: {
          dataUrlCondition: {
            maxSize: 10 * 1024 // 10kb
          }
        },
        generator: {
          filename: utils.assetsPath("media/[name].[hash:7][ext]")
        }
      },
      {
        test: /\.(woff2?|eot|ttf|otf)(\?.*)?$/,
        type: 'asset',
        parser: {
          dataUrlCondition: {
            maxSize: 10 * 1024 // 10kb
          }
        },
        generator: {
          filename: utils.assetsPath("fonts/[name].[hash:7][ext]")
        }
      },
      // webpack rules for scss (likely in `webpack.base.conf.js` or `webpack.module.rules`)
      {
        test: /\.s[ac]ss$/i,
        exclude: /\.vue$/,
        resourceQuery: /lang=scss/, // <style lang="scss">
        use: [
          'vue-style-loader',
          'css-loader',
          'postcss-loader',
          {
            loader: 'sass-loader',
            options: {
              additionalData: `@import "@/assets/_settings.scss";`
            }
          }
        ]
      },
      {
        test: /\.css$/,
        use: [MiniCssExtractPlugin.loader, "css-loader"],
      },
    ],
  },
  plugins: [
    // Ensure process is provided first, before any other plugins run
    new webpack.ProvidePlugin({
      process: 'process',
      Buffer: ['buffer', 'Buffer']
    }),
    
    new webpack.DefinePlugin({ ...definitions }),
    new webpack.DefinePlugin({
      'process.env': JSON.stringify(dotenv.parsed),
      '__VUE_OPTIONS_API__': JSON.stringify(true),
      '__VUE_PROD_DEVTOOLS__': JSON.stringify(false),
      '__VUE_PROD_HYDRATION_MISMATCH_DETAILS__': JSON.stringify(false),
      'process.env.NODE_ENV': JSON.stringify(process.env.NODE_ENV || 'development')
    }),
    
    new VueLoaderPlugin(),
    new webpack.ProvidePlugin({
      $: "jquery",
      jquery: "jquery",
      "window.jQuery": "jquery",
      jQuery: "jquery",
    }),
    new HtmlWebpackPlugin({
      template: path.resolve(__dirname, '../static/index.html'),
      inject: true,
      publicPath: '/'
    }),
    new MiniCssExtractPlugin({
      filename: utils.assetsPath("css/[name].[contenthash].css"),
      chunkFilename: utils.assetsPath("css/[id].[contenthash].css"),
    }),
    new CopyWebpackPlugin({
      patterns: [
        {
          from: path.resolve(__dirname, '../static/favicon.ico'),
          to: path.resolve(buildAssetsRoot, 'favicon.ico')
        }
      ]
    }),
  ],
};