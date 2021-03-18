const presets = [
  [
    "@babel/env",
    {
      targets: {
        edge: "17",
        firefox: "60",
        chrome: "67",
        safari: "6",
      },
      useBuiltIns: "usage",
      corejs: "3.6.4",
    },
  ],
];

const plugins =["@babel/plugin-transform-arrow-functions"]

module.exports = { presets, plugins };