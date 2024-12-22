<x-layout>
    <div class="results">
        <?php if (empty($studios)) : ?>
            <p>No results found.</p>
        <?php else : ?>
            <?php foreach ($studios as $studio) : ?>
                <div class="studio">
                    <h2>
                        <a href="{{route('studios.single', $studio->id)}}"><?php echo htmlspecialchars($studio['name']); ?></a>
                    </h2>
                    <p><?php echo htmlspecialchars($studio['address']); ?></p>
                    <p>Genres: 
                        <?php foreach ($studio['classes'] as $class) : ?>
                            <?php echo htmlspecialchars($class['genre']); ?>
                        <?php endforeach; ?>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</x-layout>