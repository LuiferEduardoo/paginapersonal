const contentInstagram = document.getElementById('content-instagram');

// const apiInstagram = 'https://instagram28.p.rapidapi.com/medias?user_id=39398725226&batch_size=5';

const apiTwiteer ='';

const optionsInstagram = {
	method: 'GET',
	headers: {
		'X-RapidAPI-Key': 'b229753591mshced7984ddc49a36p120e7cjsn9ffdd0ac972e',
		'X-RapidAPI-Host': 'instagram28.p.rapidapi.com'
	}
};

async function fetchData(urlApi, options) {
    const response = await fetch(urlApi, options); 
    const data = await response.json(); 
    return data; 
}

(async ()=> {
    try {
        const post = await fetchData(apiInstagram, optionsInstagram); 
        let view = `
        ${post.data.user.edge_owner_to_timeline_media.edges.map(post => `
        <a href="https://www.instagram/p/${post.node.shortcode}" target="_blank">
        <div class="items">
            <div>
                <img src="https://i.blogs.es/ceda9c/dalle/450_1000.jpg" alt="Imagen">
            </div>
            <div>
                <h3">
                <span></span>
                ${post.node.edge_media_to_caption.edges[0].node.text}
                </h3>
            </div>
        </div></a>`).join('')}`;
        contentInstagram.innerHTML = view;
    }
    catch(error){
        console.error(error);
    }
})();