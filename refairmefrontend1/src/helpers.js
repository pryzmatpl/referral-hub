var Base64 = require('js-base64').Base64

const HASH_SEPARATOR = '~'
const HASH_BASE = 'prizm'

export default {
  iwahash (particle, origin = 'prizm') {
    return Base64.encode(origin + '~' + particle);
  },
  
  cleandata (particle) {
    var elems = Base64.decode(particle)
    var immediate = elems.split(HASH_SEPARATOR)
    var data = immediate.slice(1)
    var dehashedParticle = data.toString().split('~')
    return dehashedParticle
  },

  cleanhash (particle) {
    if (particle === "") {
      alert('Endpoint cannot be empty to retrieve next hash');
    }

    //console.log("Particle : " + particle)
    var elems = Base64.decode(particle)
    //console.log("Elems : " + elems)

    var explodedElems = elems.split(HASH_SEPARATOR)
    return decodeURI(explodedElems[0])
  },

  iwadehash: function (particle, data = '') {
    if (particle === "FRESH") {
      return
    }

    if (particle === HASH_BASE) {
      return data
    }

    var nexthash = this.cleanhash(particle)
    var data = this.cleandata(particle)

    if (data === this.iwadehash(nexthash, data)) {
      return data
    } else {
      return data + HASH_SEPARATOR + this.iwadehash(nexthash, data)
    }
  }
}
