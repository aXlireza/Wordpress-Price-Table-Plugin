// Check if the specific ID exists
if (specificIdElement) {
	document.addEventListener('DOMContentLoaded', () => {
		if (!specificIdElement.querySelector('#btn-factories')) return;
		// switch between factories/sizes render
		specificIdElement.querySelector('#btn-factories').addEventListener('click', () => {
			specificIdElement.querySelector('#btn-sizes').classList.remove('active')
			specificIdElement.querySelector('#btn-factories').classList.add('active')
			specificIdElement.querySelector('#pricetable_mainbody_by_factory').classList.remove('hidden');
			specificIdElement.querySelector('#pricetable_mainbody_by_size').classList.add('hidden');
		});
		specificIdElement.querySelector('#btn-sizes').addEventListener('click', () => {
			specificIdElement.querySelector('#btn-sizes').classList.add('active')
			specificIdElement.querySelector('#btn-factories').classList.remove('active')
			specificIdElement.querySelector('#pricetable_mainbody_by_factory').classList.add('hidden');
			specificIdElement.querySelector('#pricetable_mainbody_by_size').classList.remove('hidden');
		});

		// tag-input-container
		[...specificIdElement.querySelectorAll(".tag-input-container")].forEach(container => {
			const container_tagname = container.getAttribute('data')
			const thetags = filter_data_tags[container_tagname]
			
			const tagInput = container.querySelector('.tag-input');
			const tagList = container.querySelector('.tag-list');
			const selectedTagsContainer = container.querySelector('.selected-tags');
			let selectedTags = [];
		
			function filterTags(init) {
				const input = tagInput.value.toLowerCase();
				let filteredTags;
			
				if (input === '') {
					filteredTags = thetags.filter(tag => !selectedTags.find(t => t.id === tag.id));
				} else {
					filteredTags = thetags.filter(tag => tag.title.toLowerCase().includes(input) && !selectedTags.find(t => t.id === tag.id));
				}
			
				updateTagList(filteredTags, init);
			}

			// Attach the filterTags function as an event listener to the input element
			document.querySelector('.tag-input').addEventListener('input', filterTags);

			function compareFactoryTables() {
				if (container_tagname == 'factory_tags') {
					const factoryTables = specificIdElement.querySelectorAll('.factory_table');
					
					if (selectedTags.length > 0) {
						factoryTables.forEach(section => {
							const sectionId = section.getAttribute('id');
							const isMatch = selectedTags.some(tag => tag.id === Number(sectionId));
						
							if (isMatch) {
								// section.style.display = 'block'; // or your preferred style for showing
								section.classList.remove('factory_tags_hidden'); // or your preferred style for showing
							} else {
								// section.style.display = 'none'; // or your preferred style for hiding
								section.classList.add('factory_tags_hidden'); // or your preferred style for hiding
							}
						});
					} else {
						// factoryTables.forEach(section => section.style.display = 'block')
						factoryTables.forEach(section => section.classList.remove('factory_tags_hidden'))
					}
				} else {
					const rows = specificIdElement.querySelectorAll('.info_row_container');
					console.log(selectedTags);
					if (selectedTags.length > 0) {
						rows.forEach(section => {
							const row_size = fa_to_en_num(section.querySelector('.row-size').innerText);
							const isMatch = selectedTags.some(tag => tag.id === Number(row_size));
						
							if (isMatch) {
								// section.style.display = 'block'; // or your preferred style for showing
								section.classList.remove('size_tags_hidden'); // or your preferred style for showing
							} else {
								// section.style.display = 'none'; // or your preferred style for hiding
								section.classList.add('size_tags_hidden'); // or your preferred style for hiding
							}
						});
					} else {
						// rows.forEach(section => section.style.display = 'block')
						rows.forEach(section => section.classList.remove('size_tags_hidden'))
					}
				}
			}

			function updateTagList(filteredTags, init) {
				tagList.innerHTML = '';			
				if (filteredTags.length > 0) {
					if (init) toggletaglist(true)
					filteredTags.forEach(tag => {
						const div = document.createElement('div');
						div.textContent = tag.title;
						div.onclick = function() { selectTag(tag); };
						tagList.appendChild(div);
					});
				} else {
					toggletaglist(false)
				}
			}

			function toggletaglist(bool) {
				bool ? tagList.classList.remove('hidden') : tagList.classList.add('hidden')
			}
		
			function selectTag(tag, init=true) {
				// selectedTags.push(tag);
				if (!selectedTags.includes(tag)) {
					selectedTags.push(tag);
					const tagElement = document.createElement('span');
					tagElement.classList.add('selected-tag');
					tagElement.textContent = tag.title;
					tagElement.setAttribute('data-id', tag.id); // Set the data-id attribute
					tagElement.onclick = function() { removeTag(tag.id); }; // Pass the id to the remove function
					selectedTagsContainer.appendChild(tagElement);
				}
				
				tagInput.value = '';
				filterTags(init); // Update the tag list to reflect the changes
				compareFactoryTables();
			}
		
			function removeTag(tagId) {
				selectedTags = selectedTags.filter(t => t.id !== tagId);

				// Find and remove the tag element from the selected tags container
				const tagElements = selectedTagsContainer.getElementsByClassName('selected-tag');
				for (let i = 0; i < tagElements.length; i++) {
					if (Number(tagElements[i].getAttribute('data-id')) === tagId) {
						selectedTagsContainer.removeChild(tagElements[i]);
						break;
					}
				}

				compareFactoryTables();
			}
		
			tagInput.addEventListener('keydown', function(event) {
				if (event.key === 'Enter') {
					event.preventDefault();
					const currentInput = tagInput.value.trim();
					if (currentInput && thetags.includes(currentInput) && !selectedTags.includes(currentInput)) {
						selectTag(currentInput);
					}
				}
			});
		
			tagInput.addEventListener('blur', function() {
				// Delay hiding the tag list to allow for click event to register
				setTimeout(() => {
					tagList.classList.add('hidden');
					toggletaglist(false)
				}, 200);
			});

			// Attach the filterTags function as an event listener to the input element for 'input' events
			// tagInput.addEventListener('input', filterTags);

			// Event listener for input focus
			tagInput.addEventListener('focus', function() {
				filterTags(); // Call filterTags to update the tag list
				tagList.classList.remove('hidden'); // Show the tag list
			});

			// Optional: Hide the tag list when the input loses focus
			tagInput.addEventListener('blur', function() {
				// Delay hiding the tag list to allow for click event to register
				setTimeout(() => {
					tagList.classList.add('hidden');
				}, 200);
			});

			// filter by url parameters
			function getQueryParams() {
				// const params = new URLSearchParams(window.location.search);
				const sizeindex = window.location.search.indexOf('size') >=0 ? window.location.search.indexOf('size') : window.location.href.length
				const requested_tags = window.location.search.slice(1).slice(0, sizeindex-1).slice(3).split(",")
				const requested_sizes = window.location.search.slice(1).slice(sizeindex-1).slice(4).split(",")
				return {
					factory_tags: requested_tags,
					size_tags: requested_sizes
				};
			}
			
			function initializeTagsFromQueryParams() {
				const queryParams = getQueryParams();
				if (container_tagname == 'size_tags' && queryParams.size_tags) {
					queryParams.size_tags.forEach(tagId => {
						const size_tags = filter_data_tags['size_tags'].find(t => t.id === Number(tagId))
						if (size_tags) selectTag(size_tags, false)
					});
				}
				if (container_tagname == 'factory_tags' && queryParams.factory_tags) {
					queryParams.factory_tags.forEach(tagId => {
						const factory_tags = filter_data_tags['factory_tags'].find(t => t.id === Number(tagId))
						if (factory_tags) selectTag(factory_tags, false)
					});
				}
			}
			// Call this function to initialize tags based on the URL query parameters
			initializeTagsFromQueryParams();
		})
	});
	
}
