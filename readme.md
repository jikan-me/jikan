![Jikan](http://i.imgur.com/ctoJ3Jp.png)

# Jikan - The Unofficial MyAnimeList.net PHP API
Bleeding edge, re-write of the Jikan PHP API. Focusing on speed, stability and maintainability.

## Dependencies
- fabpot/goutte

## Refactoring Process
### Phase 1
- [X] **Core**
- [X] Modal Class
- Data Response enhancement methods [#147](/../../issues/147), [#153](/../../issues/153)
	- [X] HTML quotes
	- [X] Trims
	- [X] UTF8 Encodes
	- [X] Null for empty

### Phase 2
- Anime
	- [X] Main
	- [X] Characters & Staff
	- [X] Episodes
	- [X] News
	- [X] Videos
	- [ ] Stats
	- [X] Pictures
	- [ ] Forum
	- [ ] More Info
- Manga
	- [X] Main
	- [ ] Characters
	- [X] News
	- [ ] Stats
	- [X] Pictures
	- [ ] Forum
	- [ ] More Info
- Person
	- [X] Main
	- [X] Pictures
- Character
	- [X] Main
	- [X] Pictures
- [ ] Schedule
- Search
	- [X] Anime
	- [ ] Manga
	- [ ] Character
	- [ ] Person
- [X] Seasonal
- [X] Schedule
- Top
    - [ ] Anime
    - [ ] Manga

#### NEW!
- Genre
    - [X] Anime
    - [X] Manga
- User
    - [X] Profile
    - [X] Friends
    - [ ] History
- [X] Producers
- [X] Studios
- [X] Magazines
- Top
    - [ ] Person
    - [ ] Character


### Phase 3
- [ ] Code Clean Up